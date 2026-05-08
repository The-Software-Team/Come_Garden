<?php

namespace App\Services;

use App\Models\Member;
use App\Models\Plot\Plot;
use App\Models\Plot\PlotInfection;
use App\Contracts\Plot\PlotServiceInterface;
use App\Support\ServiceResult;

class PlotService implements PlotServiceInterface
{
    // ── Soil state rules (mirrors Python update_soil_state) ───────────────────

    /**
     * Recomputes soil_quality from the plot's last 10 activities.
     *
     * Priority order (matches Python):
     *  1. Last fertiliser was organic      → 'recovering'
     *  2. Last 3 crops all same type       → 'depleted'
     *  3. Last 2+ crops have diversity     → 'healthy'
     *  4. Default fallback                 → 'neutral'
     */
    public function updateSoilState(Plot $plot): string
    {
        $recentActivities = $plot->activities()
            ->latest()
            ->take(10)
            ->get();

        // --- Rule 1: organic fertiliser → recovering (highest priority) ---
        $lastFertiliser = $recentActivities
            ->firstWhere('type', 'fertilize');

        if ($lastFertiliser && ($lastFertiliser->fertilizer ?? null) === 'organic') {
            $plot->update(['soil_quality' => 'recovering']);
            return 'recovering';
        }

        // --- Collect last 3 plant activities ---
        $recentCrops = $recentActivities
            ->where('type', 'plant')
            ->take(3)
            ->pluck('crop')
            ->values();

        // --- Rule 2: same crop repeated 3 times → depleted ---
        if ($recentCrops->count() >= 3 && $recentCrops->unique()->count() === 1) {
            $plot->update(['soil_quality' => 'depleted']);
            return 'depleted';
        }

        // --- Rule 3: crop diversity → healthy ---
        if ($recentCrops->count() >= 2 && $recentCrops->unique()->count() > 1) {
            $plot->update(['soil_quality' => 'healthy']);
            return 'healthy';
        }

        // --- Default fallback ---
        $plot->update(['soil_quality' => 'neutral']);
        return 'neutral';
    }

    // ── Crop management ───────────────────────────────────────────────────────

    public function plantCrop(Plot $plot, Member $user, string $cropType): ServiceResult
    {
        $plot->crops()->create([
            'user_id' => $user->id,
            'type'    => $cropType,
            'stage'   => 'planted',
        ]);

        // Log activity for soil tracking
        $plot->activities()->create([
            'type'      => 'plant',
            'member'    => $user->name,
            'crop'      => $cropType,
        ]);

        // Recompute soil state after every planting
        $this->updateSoilState($plot);

        return ServiceResult::success([], "{$cropType} planted successfully.");
    }

    // ── Infection management ──────────────────────────────────────────────────

    public function reportInfection(Plot $plot, string $type): ServiceResult
    {
        $infection = $plot->infections()->create([
            'type'           => $type,
            'infection_date' => now(),
            'severity'       => 'medium',
        ]);

        $this->alertNeighbors($plot, $infection);

        return ServiceResult::success([], 'Infection reported and neighbors alerted.');
    }

    public function alertNeighbors(Plot $plot, PlotInfection $infection): ServiceResult
    {
        foreach ($plot->neighbors as $neighbor) {
            $neighbor->infections()->create([
                'type'           => "Nearby alert: {$infection->type}",
                'infection_date' => now(),
                'severity'       => 'warning',
            ]);
        }

        return ServiceResult::success([], 'Neighbors alerted successfully.');
    }

    // ── Fertilizer management ─────────────────────────────────────────────────
    
    /**
     * Adds a fertilizer application to the plot's activity log,
     * then recomputes soil state.
     *
     * Organic fertiliser is the only type that triggers 'recovering'
     * state (see updateSoilState Rule 1).
     */
    public function addFertilizer(Plot $plot, Member $user, string $type): ServiceResult
    {
        $plot->activities()->create([
            'type'       => 'fertilize',
            'member'     => $user->name,
            'fertilizer' => $type,
        ]);
    
        $newSoilState = $this->updateSoilState($plot);
    
        $message = match ($newSoilState) {
            'recovering' => "Organic fertiliser applied — soil is now recovering ",
            'healthy'    => "Fertiliser applied — soil remains healthy.",
            'depleted'   => "Fertiliser applied, but soil is still depleted. Try organic compost.",
            default      => "Fertiliser applied successfully.",
        };
    
        return ServiceResult::success(['soil_state' => $newSoilState], $message);
    }
    

    // ── Watering schedule ─────────────────────────────────────────────────────

    /**
     * Generates a per-crop watering schedule based on:
     * - crop type (some crops need more water)
     * - sun_profile (east/west plots dry out at different times)
     * - soil_quality (depleted soil needs extra moisture)
     *
     * Returns an array of schedule entries the blade iterates over.
     */
    public function generateWateringSchedule(Plot $plot): array
    {
        if ($plot->crops->isEmpty()) {
            return [];
        }

        // Base frequency by sun profile
        $sunProfile  = $plot->sun_profile ?? 'center';
        $baseFreq    = match ($sunProfile) {
            'east'  => 'Every morning (6–8 AM)',   // dries out early
            'west'  => 'Every evening (6–8 PM)',   // afternoon sun is intense
            default => 'Morning & evening',
        };

        // Extra note when soil is struggling
        $soilNote = match ($plot->soil_quality ?? 'neutral') {
            'depleted'   => ' — add extra water, soil is depleted',
            'recovering' => ' — moderate watering while recovering',
            default      => '',
        };

        // Crops that need more frequent watering
        $thirstyCrops = ['tomato', 'tomatoes', 'cucumber', 'courgette', 'pepper', 'lettuce', 'spinach'];

        $schedule = [];
        foreach ($plot->crops as $crop) {
            $cropLower = strtolower($crop->type);
            $isThirsty = collect($thirstyCrops)->contains(fn($t) => str_contains($cropLower, $t));

            $schedule[] = [
                'crop'     => $crop->type,
                'time'     => $isThirsty
                    ? 'Twice daily (morning & evening)' . $soilNote
                    : $baseFreq . $soilNote,
                'amount'   => $isThirsty ? '2–3 litres' : '1–2 litres',
                'thirsty'  => $isThirsty,
            ];
        }

        return $schedule;
    }

    // ── Winter tasks ──────────────────────────────────────────────────────────

    /**
     * Generates contextual winter tasks based on:
     * - current crops planted
     * - infection history
     * - soil state
     * - sun profile (west plots lose warmth earlier)
     */
    public function generateWinterTasks(Plot $plot): array
    {
        $tasks = [];

        // Always-present structural tasks
        $tasks[] = ['icon' => 'ti-shovel',      'task' => 'Turn and aerate the soil before the first frost.'];
        $tasks[] = ['icon' => 'ti-droplet-off', 'task' => 'Drain and store irrigation hoses and connectors.'];

        // Soil-specific tasks
        $soilState = $plot->soil_quality ?? 'neutral';
        if ($soilState === 'depleted') {
            $tasks[] = ['icon' => 'ti-plant-2', 'task' => 'Apply compost or organic matter — soil is depleted.'];
            $tasks[] = ['icon' => 'ti-refresh',  'task' => 'Consider a green manure cover crop (e.g. clover) over winter.'];
        } elseif ($soilState === 'recovering') {
            $tasks[] = ['icon' => 'ti-plant-2', 'task' => 'Continue organic treatment — soil is recovering well.'];
        } else {
            $tasks[] = ['icon' => 'ti-sparkles', 'task' => 'Add a light layer of mulch to maintain soil health over winter.'];
        }

        // Crop-specific tasks
        if ($plot->crops->isNotEmpty()) {
            $cropTypes = $plot->crops->pluck('type')->map(fn($t) => strtolower($t));

            if ($cropTypes->contains(fn($t) => str_contains($t, 'potato'))) {
                $tasks[] = ['icon' => 'ti-archive', 'task' => 'Harvest and store remaining potatoes before ground freezes.'];
            }
            if ($cropTypes->contains(fn($t) => str_contains($t, 'tomato'))) {
                $tasks[] = ['icon' => 'ti-cut',     'task' => 'Remove tomato stakes and clear any blight-affected foliage.'];
            }
            if ($cropTypes->contains(fn($t) => str_contains($t, 'bean') || str_contains($t, 'pea'))) {
                $tasks[] = ['icon' => 'ti-leaf',    'task' => 'Leave bean/pea roots in soil — they fix nitrogen for spring.'];
            }

            $tasks[] = ['icon' => 'ti-trash',   'task' => 'Clear all spent crop debris to prevent overwintering pests.'];
        }

        // Infection-driven tasks
        if ($plot->infections->isNotEmpty()) {
            $tasks[] = ['icon' => 'ti-shield',  'task' => 'Treat infected areas before covering — do not compost diseased material.'];
            $tasks[] = ['icon' => 'ti-alert-triangle', 'task' => 'Inform the warden of any persistent infections before season close.'];
        }

        // Sun-profile tasks
        $sunProfile = $plot->sun_profile ?? 'center';
        if ($sunProfile === 'west') {
            $tasks[] = ['icon' => 'ti-temperature-minus', 'task' => 'West plots cool quickly — insulate with fleece before late October.'];
        } elseif ($sunProfile === 'east') {
            $tasks[] = ['icon' => 'ti-sunrise', 'task' => 'East plots get morning frost first — check for frost damage early.'];
        }

        return $tasks;
    }
}