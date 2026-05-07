<?php

namespace App\Services;

use App\Models\Plot\Plot;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Generates and persists a complete allotment layout.
 *
 * All coordinates use the allotment center as the origin (0, 0).
 * Positive X → east, positive Y → north.
 */
class PlotGeneratorService
{
    // ── Plot dimension config ─────────────────────────────────────────────────

    private const PLOTS = [
        'large' => ['w' => 10.0, 'h' => 20.0],
        'small' => ['w' => 5.0,  'h' => 10.0],
    ];

    // Tolerance (metres) used when deciding if two plots share an edge
    private const NEIGHBOR_TOL = 2.5;

    // ── Public entry point ────────────────────────────────────────────────────

    /**
     * Generate all plots for one allotment and persist them to the database.
     *
     * @param  float  $allotmentWidth   Total allotment width  (metres)
     * @param  float  $allotmentHeight  Total allotment height (metres)
     * @param  float  $road             Road / gap width between plots (metres)
     * @param  int    $plotIdStart      First plot ID offset (useful when appending to existing data)
     * @return array{
     *     plots: Collection,
     *     total_large: int,
     *     total_small: int,
     *     total: int
     * }
     */
    public function generateAndPersist(
        float $allotmentWidth,
        float $allotmentHeight,
        float $road,
        int   $plotIdStart = 1
    ): array {
        [$largePts, $smallPts] = $this->generateLayout($allotmentWidth, $allotmentHeight, $road);

        $plots = collect();

        DB::transaction(function () use (
            $largePts, $smallPts,
            $allotmentWidth, $allotmentHeight,
            $plots
        ) {
            // ── 1. Insert large plots ─────────────────────────────────────
            [$lw, $lh] = [self::PLOTS['large']['w'], self::PLOTS['large']['h']];

            foreach ($largePts as [$x, $y]) {
                $plots->push(
                    $this->createPlot('large', $x, $y, $lw, $lh, $allotmentWidth)
                );
            }

            // ── 2. Insert small plots ─────────────────────────────────────
            [$sw, $sh] = [self::PLOTS['small']['w'], self::PLOTS['small']['h']];

            foreach ($smallPts as [$x, $y]) {
                $plots->push(
                    $this->createPlot('small', $x, $y, $sw, $sh, $allotmentWidth)
                );
            }

            // ── 3. Assign neighbors ───────────────────────────────────────
            $this->assignNeighbors($plots);
        });

        return [
            'plots'       => $plots,
            'total_large' => count($largePts),
            'total_small' => count($smallPts),
            'total'       => $plots->count(),
        ];
    }

    // ── Layout generation (pure calculation, no DB) ───────────────────────────

    /**
     * Returns two arrays of (x, y) centre-points: [largePts, smallPts].
     */
    private function generateLayout(float $altW, float $altH, float $road): array
    {
        [$lw, $lh] = [self::PLOTS['large']['w'], self::PLOTS['large']['h']];

        // ── Large plots (greedy fill) ─────────────────────────────────────
        $stepLx = $lw + $road;
        $stepLy = $lh + $road;

        $nlpW = (int) (($altW - $road) / $stepLx);
        $nlpH = (int) (($altH - $road) / $stepLy);

        $usedWidth  = $nlpW * $stepLx;
        $usedHeight = $nlpH * $stepLy;

        $startLx = -($altW / 2) + $road + $lw / 2;
        $startLy = -($altH / 2) + $road + $lh / 2;

        $largePts = $this->generatePoints($startLx, $startLy, $stepLx, $stepLy, $nlpW, $nlpH);

        // ── Small plots ───────────────────────────────────────────────────
        [$sw, $sh] = [self::PLOTS['small']['w'], self::PLOTS['small']['h']];
        $stepSx = $sw + $road;
        $stepSy = $sh + $road;

        $remainingWidth  = $altW - $usedWidth;
        $remainingHeight = $altH - $usedHeight;

        $smallPts = [];

        // Right strip
        if ($remainingWidth >= $sw) {
            $nspWRight = (int) ($remainingWidth / $stepSx);
            $nspHRight = (int) (($altH - $road) / $stepSy);

            $rightEdgeLarge = -($altW / 2) + $usedWidth + $road;
            $startRx = $rightEdgeLarge + $sw / 2;
            $startRy = -($altH / 2) + $road + $sh / 2;

            $smallPts = array_merge(
                $smallPts,
                $this->generatePoints($startRx, $startRy, $stepSx, $stepSy, $nspWRight, $nspHRight)
            );
        }

        // Top strip
        if ($remainingHeight >= $sh) {
            $nspWTop = (int) ($usedWidth / $stepSx);
            $nspHTop = (int) ($remainingHeight / $stepSy);

            $topEdgeLarge = -($altH / 2) + $usedHeight + $road;
            $startTx = -($altW / 2) + $road + $sw / 2;
            $startTy = $topEdgeLarge + $sh / 2;

            $smallPts = array_merge(
                $smallPts,
                $this->generatePoints($startTx, $startTy, $stepSx, $stepSy, $nspWTop, $nspHTop)
            );
        }

        return [$largePts, $smallPts];
    }

    /**
     * Build a 2-D grid of (x, y) centre-points.
     */
    private function generatePoints(
        float $startX, float $startY,
        float $stepX,  float $stepY,
        int   $countX, int   $countY
    ): array {
        $pts = [];
        for ($i = 0; $i < $countX; $i++) {
            for ($j = 0; $j < $countY; $j++) {
                $pts[] = [
                    round($startX + $i * $stepX, 4),
                    round($startY + $j * $stepY, 4),
                ];
            }
        }
        return $pts;
    }

    // ── Plot creation ─────────────────────────────────────────────────────────

    private function createPlot(
        string $size,
        float  $x,
        float  $y,
        float  $w,
        float  $h,
        float  $allotmentWidth
    ): Plot {
        return Plot::create([
            'size'       => $size,
            'x'          => round($x, 4),
            'y'          => round($y, 4),
            'width'      => $w,
            'height'     => $h,
            'area'       => round($w * $h, 4),
            'x_min'      => round($x - $w / 2, 4),
            'x_max'      => round($x + $w / 2, 4),
            'y_min'      => round($y - $h / 2, 4),
            'y_max'      => round($y + $h / 2, 4),
            'sun_profile' => $this->sunProfile($x, $allotmentWidth),
            'status'     => 'available',
        ]);
    }

    /**
     * Mirrors Python's assign_sun_profile exactly:
     *   nx = (x + alt_w/2) / alt_w   → normalize to [0, 1]
     *   nx = clamp(nx, 0, 1)
     *   nx < 0.25  → 'west'  (left zone)
     *   nx < 0.75  → 'center' (middle zone)
     *   else       → 'east'  (right zone)
     */
    private function sunProfile(float $x, float $allotmentWidth): string
    {
        // Normalize: shift origin from centre to left edge, then divide by width
        $nx = ($x + $allotmentWidth / 2) / $allotmentWidth;

        // Clamp to [0, 1] to handle floating-point edge cases
        $nx = max(0.0, min(1.0, $nx));

        if ($nx < 0.25) {
            return 'west';
        }

        if ($nx < 0.75) {
            return 'center';
        }

        return 'east';
    }

    // ── Neighbor assignment ───────────────────────────────────────────────────

    private function assignNeighbors(Collection $plots): void
    {
        $rows = [];

        foreach ($plots as $p1) {
            foreach ($plots as $p2) {
                if ($p1->id === $p2->id) {
                    continue;
                }

                $direction = $this->neighborDirection($p1, $p2);

                if ($direction !== null) {
                    $rows[] = [
                        'plot_id'          => $p1->id,
                        'neighbor_plot_id' => $p2->id,
                        'direction'        => $direction,
                        'created_at'       => now(),
                        'updated_at'       => now(),
                    ];
                }
            }
        }

        // Bulk-insert; ignore duplicates in case of re-runs
        if (!empty($rows)) {
            DB::table('plot_neighbors')->insertOrIgnore($rows);
        }
    }

    /**
     * Returns the cardinal direction from p1 → p2, or null if not neighbors.
     *
     * Mirrors Python's are_neighbors() but also returns the direction label.
     */
    private function neighborDirection(Plot $p1, Plot $p2): ?string
    {
        $dx = abs($p1->x - $p2->x);
        $dy = abs($p1->y - $p2->y);

        $maxDx = ($p1->width  + $p2->width)  / 2;
        $maxDy = ($p1->height + $p2->height) / 2;

        $tol = self::NEIGHBOR_TOL;

        // Horizontally adjacent (east / west)
        if (abs($dx - $maxDx) <= $tol && $dy <= $maxDy + $tol) {
            return $p2->x > $p1->x ? 'east' : 'west';
        }

        // Vertically adjacent (north / south)
        if (abs($dy - $maxDy) <= $tol && $dx <= $maxDx + $tol) {
            return $p2->y > $p1->y ? 'north' : 'south';
        }

        return null;
    }
}