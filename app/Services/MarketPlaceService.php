<?php

namespace App\Services;

use App\Models\Market\Listing;
use App\Models\Market\Trade;
use App\Models\Market\Question;
use App\Models\Market\Answer;
use App\Models\Market\KarmaTransaction;
use App\Models\Market\QualityRating;
use App\Models\Market\CanningSession;
use App\Models\Market\CanningContributor;
use App\Models\Member;

use App\Contracts\Marketplace\MarketplaceServiceInterface as Market;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class MarketPlaceService extends BaseService implements Market
{
    /* ══════════════════════════════════════════════════════════
       CONSTANTS
       ══════════════════════════════════════════════════════════ */

    const ALLERGEN_MAP = [
        'Nightshades' => ['tomato', 'pepper', 'eggplant', 'potato', 'tomatillo', 'chilli'],
        'Tree Nuts'   => ['walnut', 'almond', 'pecan', 'hazelnut', 'chestnut', 'pistachio'],
        'Legumes'     => ['peanut', 'soybean', 'lentil', 'chickpea', 'bean', 'pea'],
        'Mustard'     => ['mustard', 'rocket', 'watercress', 'horseradish', 'radish'],
        'Celery'      => ['celery', 'celeriac'],
        'Sulphites'   => ['dried fruit', 'grape', 'raisin', 'sultana'],
        'Gluten'      => ['wheat', 'barley', 'rye', 'oat', 'spelt'],
    ];

    const KARMA_PER_GIFT_KG    = 10;
    const CREDITS_PER_ANSWER   = 5;
    const FLASH_DEFAULT_HOURS  = 2;


    /* ══════════════════════════════════════════════════════════
       LISTINGS
       ══════════════════════════════════════════════════════════ */

    public function createListing(array $data): array
    {
        return DB::transaction(function () use ($data) {

            // Resolve allergens automatically
            $allergens = $this->resolveAllergens($data['produce_name'] ?? '');
            $data['allergen_flags'] = empty($allergens) ? null : implode(',', $allergens);

            // Flash trade: set expiry
            if (($data['type'] ?? '') === 'flash') {
                $hours = $data['pickup_window_hours'] ?? self::FLASH_DEFAULT_HOURS;
                $data['expires_at'] = Carbon::now()->addHours((int) $hours);
            }

            // Gift trade: price = 0, award karma
            $isGift = ($data['type'] ?? '') === 'gift';
            if ($isGift) {
                $data['price'] = 0;
            }

            $listing = Listing::create($data);

            if ($isGift) {
                $karmaPoints = (int) (($data['quantity_kg'] ?? 1) * self::KARMA_PER_GIFT_KG);
                KarmaTransaction::create([
                    'user_id'      => $data['user_id'],
                    'points'       => $karmaPoints,
                    'reason'       => 'gift_listing',
                    'reference_id' => $listing->id,
                    'description'  => "Gifted {$data['quantity_kg']}kg of {$data['produce_name']}",
                ]);
                Member::find($data['user_id'])->increment('karma_points', $karmaPoints);
            }

            return [
                'success'  => true,
                'message'  => $isGift
                    ? 'Produce gifted to the community! Karma points awarded. 🌱'
                    : 'Listing created successfully.',
                'listing'  => $listing,
            ];
        });
    }

    public function getListings(array $filters = []): array
    {
        $query = Listing::with('user')
            ->where('status', 'available')
            ->latest();

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['allergen_free'])) {
            $query->whereNull('allergen_flags');
        }

        if (!empty($filters['search'])) {
            $query->where('produce_name', 'like', '%' . $filters['search'] . '%');
        }

        // Flash: only show non-expired
        $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', Carbon::now());
        });

        return [
            'success'  => true,
            'listings' => $query->get(),
        ];
    }

    public function getListingById(int $id): array
    {
        $listing = Listing::with(['user', 'qualityRatings.user'])->findOrFail($id);

        return [
            'success' => true,
            'listing' => $listing,
        ];
    }

    public function getMemberListings(int $userId): array
    {
        $listings = Listing::with('trades')
            ->where('user_id', $userId)
            ->latest()
            ->get();

        return [
            'success'  => true,
            'listings' => $listings,
        ];
    }


    /* ══════════════════════════════════════════════════════════
       TRADES
       ══════════════════════════════════════════════════════════ */

    public function createTrade(array $data): array
    {
        return DB::transaction(function () use ($data) {

            $listing = Listing::findOrFail($data['listing_id']);

            if ($listing->status !== 'available') {
                return ['success' => false, 'message' => 'This listing is no longer available.'];
            }

            if ($listing->user_id === $data['buyer_id']) {
                return ['success' => false, 'message' => 'You cannot trade with yourself.'];
            }

            $trade = Trade::create([
                'listing_id' => $listing->id,
                'seller_id'  => $listing->user_id,
                'buyer_id'   => $data['buyer_id'],
                'quantity'   => $data['quantity'] ?? $listing->quantity_kg,
                'status'     => 'pending',
                'note'       => $data['note'] ?? null,
            ]);

            $listing->update(['status' => 'reserved']);

            return [
                'success' => true,
                'message' => 'Trade request sent! The seller will confirm.',
                'trade'   => $trade,
            ];
        });
    }

    public function claimFlashListing(int $listingId, int $userId): array
    {
        $listing = Listing::findOrFail($listingId);
        
        if ($listing->type !== 'flash') {
            return ['success' => false, 'message' => 'This is not a flash listing.'];
        }

        if ($listing->expires_at && $listing->expires_at < Carbon::now()) {
            $listing->update(['status' => 'expired']);
            return ['success' => false, 'message' => 'This flash listing has expired.'];
        }


        return $this->createTrade([
            'listing_id' => $listingId,
            'buyer_id'   => $userId,
            'note'       => 'Flash trade claim',
        ]);
    }

    public function getMemberTrades(int $userId): array
    {
        $trades = Trade::with(['listing', 'seller', 'buyer'])
            ->where('buyer_id', $userId)
            ->orWhere('seller_id', $userId)
            ->latest()
            ->get();

        return [
            'success' => true,
            'trades'  => $trades,
        ];
    }


    /* ══════════════════════════════════════════════════════════
       KARMA  (Feature 34 — runs silently inside gift logic)
       ══════════════════════════════════════════════════════════ */

    public function getMemberKarma(int $userId): array
    {
        $user         = Member::findOrFail($userId);
        $transactions = KarmaTransaction::where('user_id', $userId)
            ->latest()
            ->limit(10)
            ->get();

        return [
            'success'      => true,
            'karma_points' => $user->karma_points ?? 0,
            'transactions' => $transactions,
        ];
    }

    public function getKarmaLeaderboard(): array
    {
        return [
            'success' => true,
            'leaders' => Member::orderByDesc('karma_points')
                ->limit(10)
                ->get(['id', 'name', 'karma_points']),
        ];
    }


    /* ══════════════════════════════════════════════════════════
       ALLERGEN GUARD  (Feature 36 — runs silently on create)
       ══════════════════════════════════════════════════════════ */

    public function resolveAllergens(string $produceName): array
    {
        $name  = strtolower($produceName);
        $flags = [];

        foreach (self::ALLERGEN_MAP as $category => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($name, $keyword)) {
                    $flags[] = $category;
                    break;
                }
            }
        }

        return array_unique($flags);
    }

    public function getAllergenCategories(): array
    {
        return array_keys(self::ALLERGEN_MAP);
    }

    public function getMemberAllergens(int $userId): array
    {
        $user = Member::findOrFail($userId);
        return [
            'success'   => true,
            'allergens' => $user->allergen_preferences
                ? explode(',', $user->allergen_preferences)
                : [],
        ];
    }

    ## TODO:
    public function getUserQualityScore(int $userId) : array {
        return ['quality_score' => 1];
    }

    public function updateMemberAllergens(int $userId, array $allergens): array
    {
        Member::findOrFail($userId)->update([
            'allergen_preferences' => implode(',', $allergens),
        ]);

        return [
            'success' => true,
            'message' => 'Allergy profile updated.',
        ];
    }


    /* ══════════════════════════════════════════════════════════
       Q&A  (Feature 37)
       ══════════════════════════════════════════════════════════ */

    public function askQuestion(array $data): array
    {
        $question = Question::create($data);

        return [
            'success'  => true,
            'message'  => 'Question posted to the community!',
            'question' => $question,
        ];
    }

    public function answerQuestion(array $data): array
    {
        return DB::transaction(function () use ($data) {

            $answer = Answer::create($data);

            // Award seed bank credits to the answerer
            Member::find($data['user_id'])->increment('seedbank_credits', self::CREDITS_PER_ANSWER);

            KarmaTransaction::create([
                'user_id'      => $data['user_id'],
                'points'       => self::CREDITS_PER_ANSWER,
                'reason'       => 'answer_credits',
                'reference_id' => $answer->id,
                'description'  => 'Earned credits for answering a community question',
            ]);

            return [
                'success' => true,
                'message' => 'Answer posted! You earned ' . self::CREDITS_PER_ANSWER . ' Seed Bank Credits.',
                'answer'  => $answer,
            ];
        });
    }

    public function getQuestions(array $filters = []): array
    {
        $query = Question::with(['user', 'answers.user'])->latest();

        if (!empty($filters['search'])) {
            $query->where('title', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['unanswered'])) {
            $query->doesntHave('answers');
        }

        return [
            'success'   => true,
            'questions' => $query->get(),
        ];
    }

    public function getQuestionById(int $id): array
    {
        $question = Question::with(['user', 'answers.user'])->findOrFail($id);

        return [
            'success'  => true,
            'question' => $question,
        ];
    }

    public function getMemberQuestions(int $userId): array
    {
        return [
            'success'   => true,
            'questions' => Question::with('answers')
                ->where('user_id', $userId)
                ->latest()
                ->get(),
        ];
    }

    public function getMemberAnswers(int $userId): array
    {
        return [
            'success' => true,
            'answers' => Answer::with('question')
                ->where('user_id', $userId)
                ->latest()
                ->get(),
        ];
    }


    /* ══════════════════════════════════════════════════════════
       QUALITY RATING  (Feature 38)
       ══════════════════════════════════════════════════════════ */

    public function submitQualityRating(array $data): array
    {
        $existing = QualityRating::where('listing_id', $data['listing_id'])
            ->where('user_id', $data['user_id'])
            ->first();

        if ($existing) {
            return ['success' => false, 'message' => 'You have already rated this listing.'];
        }

        $rating = QualityRating::create($data);

        // Recompute average on the listing
        $avg = QualityRating::where('listing_id', $data['listing_id'])->avg('score');
        Listing::find($data['listing_id'])->update(['quality_score' => round($avg, 1)]);

        return [
            'success' => true,
            'message' => 'Quality rating submitted. Thank you!',
            'rating'  => $rating,
        ];
    }

    public function getListingRatings(int $listingId): array
    {
        return [
            'success' => true,
            'ratings' => QualityRating::with('user')
                ->where('listing_id', $listingId)
                ->latest()
                ->get(),
        ];
    }

    public function getMemberQualityScore(int $userId): array
    {
        $avg = Listing::where('user_id', $userId)->avg('quality_score');

        return [
            'success'       => true,
            'quality_score' => $avg ? round($avg, 1) : null,
        ];
    }


    /* ══════════════════════════════════════════════════════════
       CANNING SESSIONS  (Feature 39)
       ══════════════════════════════════════════════════════════ */

    public function createCanningSession(array $data): array
    {
        $data['status']       = 'open';
        $data['current_count'] = 0;

        $session = CanningSession::create($data);

        return [
            'success' => true,
            'message' => 'Canning session created! Members can now join.',
            'session' => $session,
        ];
    }

    public function joinCanningSession(int $sessionId, int $userId, array $contribution): array
    {
        return DB::transaction(function () use ($sessionId, $userId, $contribution) {

            $session = CanningSession::findOrFail($sessionId);

            if ($session->status !== 'open') {
                return ['success' => false, 'message' => 'This session is no longer accepting members.'];
            }

            if ($session->current_count >= $session->max_members) {
                return ['success' => false, 'message' => 'This session is full.'];
            }

            $alreadyJoined = CanningContributor::where('session_id', $sessionId)
                ->where('user_id', $userId)
                ->exists();

            if ($alreadyJoined) {
                return ['success' => false, 'message' => 'You have already joined this session.'];
            }

            CanningContributor::create([
                'session_id'    => $sessionId,
                'user_id'       => $userId,
                'produce_name'  => $contribution['produce_name'],
                'quantity_kg'   => $contribution['quantity_kg'],
            ]);

            $session->increment('current_count');

            return [
                'success' => true,
                'message' => 'You have joined the canning session! See you there. 🫙',
            ];
        });
    }

    public function getCanningSessions(array $filters = []): array
    {
        $query = CanningSession::with(['organizer', 'contributors.user'])
            ->latest();

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return [
            'success'  => true,
            'sessions' => $query->get(),
        ];
    }

    public function getCanningSessionById(int $id): array
    {
        $session = CanningSession::with(['organizer', 'contributors.user'])->findOrFail($id);

        return [
            'success' => true,
            'session' => $session,
        ];
    }

    public function getMemberCanningSessions(int $userId): array
    {
        $organised = CanningSession::with('contributors.user')
            ->where('organizer_id', $userId)
            ->latest()
            ->get();

        $joined = CanningSession::with('contributors.user')
            ->whereHas('contributors', fn($q) => $q->where('user_id', $userId))
            ->where('organizer_id', '!=', $userId)
            ->latest()
            ->get();

        return [
            'success'   => true,
            'organised' => $organised,
            'joined'    => $joined,
        ];
    }

    public function getUserListings(int $userId): array
    {
        return $this->getMemberListings($userId);
    }

    public function getUserTrades(int $userId): array
    {
        return $this->getMemberTrades($userId);
    }

    public function getUserKarma(int $userId): array
    {
        return $this->getMemberKarma($userId);
    }

    public function getUserAllergens(int $userId): array
    {
        return $this->getMemberAllergens($userId);
    }

    public function updateUserAllergens(int $userId, array $allergens): array
    {
        return $this->updateMemberAllergens($userId, $allergens);
    }

    public function getUserQuestions(int $userId): array
    {
        return $this->getMemberQuestions($userId);
    }

    public function getUserAnswers(int $userId): array
    {
        return $this->getMemberAnswers($userId);
    }

    public function getUserCanningSessions(int $userId): array
    {
        return $this->getMemberCanningSessions($userId);
    }


    /* ══════════════════════════════════════════════════════════
       SURPLUS PREDICTION  (Feature 35 — background alert)
       ══════════════════════════════════════════════════════════ */

    public function predictSurplus(int $userId): array
    {
        // Pull active plantings from user's plots and estimate
        // harvest dates vs current listings to flag upcoming surplus.
        // This runs as a scheduled job and surfaces alerts in the profile.

        $plantings = \App\Models\Plot\PlotCrop::with('plot')
            ->where('user_id', $userId)
            ->where('stage', 'planted')
            ->get();

        $predictions = $plantings->map(function ($planting) {
            $daysToHarvest = Carbon::now()->diffInDays($planting->expected_harvest_at, false);

            return [
                'produce'          => $planting->crop_name,
                'plot'             => $planting->plot->name ?? '—',
                'days_to_harvest'  => $daysToHarvest,
                'expected_yield_kg'=> $planting->expected_yield_kg ?? null,
                'alert'            => $daysToHarvest <= 14 && $daysToHarvest >= 0,
            ];
        })->filter(fn($p) => $p['alert']);

        return [
            'success'     => true,
            'predictions' => $predictions->values(),
        ];
    }


    /* ══════════════════════════════════════════════════════════
       ADMIN OVERVIEW
       ══════════════════════════════════════════════════════════ */

    public function getAdminOverview(): array
    {
        return [
            'success' => true,
            'stats'   => [
                'total_listings'       => Listing::count(),
                'active_listings'      => Listing::where('status', 'available')->count(),
                'flash_active'         => Listing::where('type', 'flash')
                                            ->where('status', 'available')
                                            ->where('expires_at', '>', Carbon::now())
                                            ->count(),
                'gift_listings'        => Listing::where('type', 'gift')->count(),
                'total_trades'         => Trade::count(),
                'pending_trades'       => Trade::where('status', 'pending')->count(),
                'total_questions'      => Question::count(),
                'unanswered_questions' => Question::doesntHave('answers')->count(),
                'canning_sessions'     => CanningSession::count(),
                'open_sessions'        => CanningSession::where('status', 'open')->count(),
                'flagged_allergens'    => Listing::whereNotNull('allergen_flags')
                                            ->where('status', 'available')
                                            ->count(),
                'top_karma_user'       => Member::orderByDesc('karma_points')
                                            ->first(['name', 'karma_points']),
            ],
            'recent_listings'  => Listing::with('user')->latest()->limit(5)->get(),
            'recent_trades'    => Trade::with(['listing', 'buyer', 'seller'])->latest()->limit(5)->get(),
            'flagged_listings' => Listing::with('user')
                                    ->whereNotNull('allergen_flags')
                                    ->where('status', 'available')
                                    ->latest()
                                    ->limit(10)
                                    ->get(),
        ];
    }
}