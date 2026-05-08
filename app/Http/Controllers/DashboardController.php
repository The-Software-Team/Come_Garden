<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Plot\Plot;
use App\Models\RentalParticipant;
use App\Models\Season;

use App\Models\ToolLibrary\Tool;
use App\Models\Volunteer\Shift;

use App\Models\Market\Listing;
use App\Models\Market\Trade;
use App\Models\ToolLibrary\Booking;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /* ══════════════════════════════════════════════════════════
       MEMBER DASHBOARD
       ══════════════════════════════════════════════════════════ */

    public function member()
    {
        $member = auth()->user();

        // ── Plot ─────────────────────────────────────────────────
        // Member is a rental participant — find their active rental
        $activeParticipant = RentalParticipant::where('member_id', $member->id)
            ->where('status', 'active')
            ->with([
                'rental.plot.crops',
                'rental.plot.infections',
            ])
            ->latest()
            ->first();

        $plot   = $activeParticipant?->rental?->plot;
        $rental = $activeParticipant?->rental;

        // ── Seedbank ─────────────────────────────────────────────
        $seedbankProfile = null;
        $seedCount       = 10;

        // ── Marketplace ───────────────────────────────────────────
        $activeListings = Listing::where('user_id', $member->id)
            ->where('status', 'available')
            ->count();

        $pendingTrades = Trade::where(function ($q) use ($member) {
                $q->where('buyer_id',  $member->id)
                  ->orWhere('seller_id', $member->id);
            })
            ->where('status', 'pending')
            ->count();

        // ── Tools ────────────────────────────────────────────────
        $activeBookings = Booking::where('member_id', $member->id)
            ->where('status', 'active')
            ->count();

        // ── Volunteer ────────────────────────────────────────────
        $upcomingShifts = Shift::whereHas('assignments', function ($q) use ($member) {
                $q->where('member_id', $member->id)
                  ->where('status', 'assigned');
            })
            ->where('end_date', '>=', now())
            ->orderBy('end_date')
            ->limit(3)
            ->get();

        return view('dashboard.member', compact(
            'member',
            'plot',
            'rental',
            'activeParticipant',
            'seedbankProfile',
            'seedCount',
            'activeListings',
            'pendingTrades',
            'activeBookings',
            'upcomingShifts',
        ));
    }


    /* ══════════════════════════════════════════════════════════
       ADMIN DASHBOARD
       ══════════════════════════════════════════════════════════ */

    public function admin()
    {
        // ── Plots ────────────────────────────────────────────────
        $plotStats = [
            'total'       => Plot::count(),
            'available'   => Plot::where('status', 'available')->count(),
            'rented'      => Plot::where('status', 'rented')->count(),
            'infected'    => Plot::where('infection_status', true)->count(),
            'applications'=> DB::table('rental_applications')
                                ->where('status', 'pending')
                                ->count(),
        ];

        $activeSeason = Season::where('status', 'active')->first();

        // ── Marketplace ───────────────────────────────────────────
        $marketStats = [
            'active_listings'   => Listing::where('status', 'available')->count(),
            'flash_live'        => Listing::where('type', 'flash')
                                    ->where('status', 'available')
                                    ->where('expires_at', '>', now())
                                    ->count(),
            'pending_trades'    => Trade::where('status', 'pending')->count(),
            'flagged_allergens' => Listing::whereNotNull('allergen_flags')
                                    ->where('status', 'available')
                                    ->count(),
        ];

        // ── Seedbank ─────────────────────────────────────────────
        $seedStats = [
            'total_seeds'  => 10,
            'total_types'  => 20,
            'low_stock'    => 12,
        ];

        // ── Tools ────────────────────────────────────────────────
        $toolStats = [
            'total'             => Tool::count(),
            'available'         => Tool::where('status', 'available')->count(),
            'in_use'            => Tool::where('status', 'in_use')->count(),
            'maintenance_due'   => Tool::where('status', 'maintenance')->count(),
        ];

        // ── Volunteer ────────────────────────────────────────────
        $volunteerStats = [
            'open_shifts'     => Shift::where('status', 'open')
                                    ->where('end_date', '>=', now())
                                    ->count(),
            'upcoming_shifts' => Shift::where('end_date', '>=', now())
                                    ->where('end_date', '<=', now()->addDays(7))
                                    ->count(),
        ];

        // ── Recent activity feed ─────────────────────────────────
        $recentApplications = DB::table('rental_applications')
            ->join('members', 'rental_applications.member_id', '=', 'members.id')
            ->join('plots',   'rental_applications.plot_id',   '=', 'plots.id')
            ->where('rental_applications.status', 'pending')
            ->select(
                'rental_applications.id',
                'members.name as member_name',
                'plots.id as plot_id',
                'rental_applications.created_at',
            )
            ->orderByDesc('rental_applications.created_at')
            ->limit(5)
            ->get();

        return view('dashboard.admin', compact(
            'plotStats',
            'activeSeason',
            'marketStats',
            'seedStats',
            'toolStats',
            'volunteerStats',
            'recentApplications',
        ));
    }
}