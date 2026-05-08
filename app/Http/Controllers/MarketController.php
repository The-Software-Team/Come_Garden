<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Contracts\Marketplace\MarketplaceServiceInterface as Market;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarketController extends Controller
{
    public function __construct(protected Market $market) {}


    /* ══════════════════════════════════════════════════════════
       PAGES
       ══════════════════════════════════════════════════════════ */

    /**
     * The public marketplace — listings, questions, canning sessions.
     * GET /marketplace/market
     */
    public function market(Request $request)
    {
        $user     = Auth::user();
        $listings = $this->market->getListings($request->only('type', 'allergen_free', 'search'));
        $questions = $this->market->getQuestions($request->only('search', 'unanswered'));
        $sessions  = $this->market->getCanningSessions(['status' => 'open']);
        $allergens = $this->market->getAllergenCategories() ?? [];
        $surplus   = $this->market->predictSurplus($user->id);

        return view('marketplace.market', [
            'listings'         => $listings['listings'],
            'questions'        => $questions['questions'],
            'canningSessions'  => $sessions['sessions'],
            'allergenCategories' => $allergens,
            'surplusAlerts'    => $surplus['predictions'],
            'userAllergens'    => $this->market->getUserAllergens($user->id)['allergens'],
        ]);
    }

    /**
     * The member's personal dashboard — my listings, trades, questions, answers, canning.
     * GET /marketplace/profile
     */
    public function profile()
    {
        $user     = Auth::user();
        $listings = $this->market->getUserListings($user->id);
        $trades   = $this->market->getUserTrades($user->id);
        $questions = $this->market->getUserQuestions($user->id);
        $answers  = $this->market->getUserAnswers($user->id);
        $canning  = $this->market->getUserCanningSessions($user->id);
        $karma    = $this->market->getUserKarma($user->id);
        $quality  = $this->market->getUserQualityScore($user->id);

        return view('marketplace.profile', [
            'myListings'   => $listings['listings'],
            'myTrades'     => $trades['trades'],
            'myQuestions'  => $questions['questions'],
            'myAnswers'    => $answers['answers'],
            'myOrganised'  => $canning['organised'],
            'myJoined'     => $canning['joined'],
            'karma'        => $karma['karma_points'],
            'karmaLog'     => $karma['transactions'],
            'qualityScore' => $quality['quality_score'],
        ]);
    }

    /**
     * GET /admin/marketplace
     */
    public function index()
    {
        $overview = $this->market->getAdminOverview();
 
        return view('admin.marketplace.index', [
            'stats'           => $overview['stats'],
            'recentListings'  => $overview['recent_listings'],
            'recentTrades'    => $overview['recent_trades'],
            'flaggedListings' => $overview['flagged_listings'],
        ]);
    }



    /* ══════════════════════════════════════════════════════════
       LISTING ACTIONS
       ══════════════════════════════════════════════════════════ */

    /**
     * POST /marketplace/listings
     */
    public function storeListing(Request $request)
    {
        $request->validate([
            'produce_name'        => 'required|string|max:120',
            'type'                => 'required|in:standard,flash,gift',
            'quantity_kg'         => 'required|numeric|min:0.1',
            'description'         => 'nullable|string|max:500',
            'pickup_window_hours' => 'nullable|integer|min:1|max:24',
            'price'               => 'nullable|numeric|min:0',
        ]);

        $result = $this->market->createListing(array_merge(
            $request->all(),
            ['user_id' => Auth::id()]
        ));

        return back()->with(
            $result['success'] ? 'message' : 'error',
            $result['message']
        );
    }


    /* ══════════════════════════════════════════════════════════
       TRADE ACTIONS
       ══════════════════════════════════════════════════════════ */

    /**
     * POST /marketplace/trades
     */
    public function storeTrade(Request $request)
    {
        $request->validate([
            'listing_id' => 'required|exists:listings,id',
            'note'       => 'nullable|string|max:300',
        ]);

        $result = $this->market->createTrade(array_merge(
            $request->all(),
            ['buyer_id' => Auth::id()]
        ));

        return back()->with(
            $result['success'] ? 'message' : 'error',
            $result['message']
        );
    }

    /**
     * POST /marketplace/flash/claim
     */
    public function claimFlash(Request $request)
    {
        $request->validate(['listing_id' => 'required|exists:listings,id']);

        $result = $this->market->claimFlashListing(
            $request->listing_id,
            Auth::id()
        );

        return back()->with(
            $result['success'] ? 'message' : 'error',
            $result['message']
        );
    }


    /* ══════════════════════════════════════════════════════════
       Q&A ACTIONS
       ══════════════════════════════════════════════════════════ */

    /**
     * POST /marketplace/questions
     */
    public function storeQuestion(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:200',
            'body'  => 'required|string|max:1000',
            'tags'  => 'nullable|string|max:100',
        ]);

        $result = $this->market->askQuestion(array_merge(
            $request->all(),
            ['user_id' => Auth::id()]
        ));

        return back()->with(
            $result['success'] ? 'message' : 'error',
            $result['message']
        );
    }

    /**
     * POST /marketplace/answers
     */
    public function storeAnswer(Request $request)
    {
        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'body'        => 'required|string|max:1000',
        ]);

        $result = $this->market->answerQuestion(array_merge(
            $request->all(),
            ['user_id' => Auth::id()]
        ));

        return back()->with(
            $result['success'] ? 'message' : 'error',
            $result['message']
        );
    }


    /* ══════════════════════════════════════════════════════════
       QUALITY RATING
       ══════════════════════════════════════════════════════════ */

    /**
     * POST /marketplace/ratings
     */
    public function storeRating(Request $request)
    {
        $request->validate([
            'listing_id' => 'required|exists:listings,id',
            'score'      => 'required|integer|min:1|max:5',
            'comment'    => 'nullable|string|max:300',
            'organic'    => 'nullable|boolean',
        ]);

        $result = $this->market->submitQualityRating(array_merge(
            $request->all(),
            ['user_id' => Auth::id()]
        ));

        return back()->with(
            $result['success'] ? 'message' : 'error',
            $result['message']
        );
    }


    /* ══════════════════════════════════════════════════════════
       CANNING SESSIONS
       ══════════════════════════════════════════════════════════ */

    /**
     * POST /marketplace/canning
     */
    public function storeCanningSession(Request $request)
    {
        $request->validate([
            'title'              => 'required|string|max:150',
            'description'        => 'nullable|string|max:500',
            'scheduled_at'       => 'required|date|after:today',
            'location'           => 'required|string|max:200',
            'max_members'        => 'required|integer|min:2|max:30',
            'produce_target'     => 'required|string|max:200',
        ]);

        $result = $this->market->createCanningSession(array_merge(
            $request->all(),
            ['organizer_id' => Auth::id()]
        ));

        return back()->with(
            $result['success'] ? 'message' : 'error',
            $result['message']
        );
    }

    /**
     * POST /marketplace/canning/join
     */
    public function joinCanningSession(Request $request)
    {
        $request->validate([
            'session_id'   => 'required|exists:canning_sessions,id',
            'produce_name' => 'required|string|max:100',
            'quantity_kg'  => 'required|numeric|min:0.1',
        ]);

        $result = $this->market->joinCanningSession(
            $request->session_id,
            Auth::id(),
            $request->only('produce_name', 'quantity_kg')
        );

        return back()->with(
            $result['success'] ? 'message' : 'error',
            $result['message']
        );
    }


    /* ══════════════════════════════════════════════════════════
       ALLERGEN PROFILE
       ══════════════════════════════════════════════════════════ */

    /**
     * POST /marketplace/allergens
     */
    public function updateAllergens(Request $request)
    {
        $result = $this->market->updateUserAllergens(
            Auth::id(),
            $request->input('allergens', [])
        );

        return back()->with(
            $result['success'] ? 'message' : 'error',
            $result['message']
        );
    }
}