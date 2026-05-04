<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Contracts\Marketplace\MarketplaceServiceInterface;
use App\Models\Market\Listing;
use App\Models\Market\Trade;
use App\Models\Market\Question;

class MarketController extends Controller
{
    protected MarketplaceServiceInterface $service;

    public function __construct(MarketplaceServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index() {
        $listings = Listing::get();
        $questions = [];

        return view('marketplace.index', compact('listings', 'questions'));
    }

    public function profile() {
        $member = auth()->user();

        $listings = Listing::where('member_id', $member->id)->get();

        $trades = Trade::where('member_id', $member->id)->get();

        $questions = Question::with('answers')
            ->where('member_id', $member->id)
            ->get();

        return view('marketplace.profile', compact(
            'listings',
            'trades',
            'questions'
        ));

    }

    public function createListing(Request $request)
    {
        $data = $request->except('_token');

        $data['member_id'] = auth()->user()->id;
        $this->service->createListing($data);

        return redirect()->back()->with('message', 'Listing action not implemented yet');
    }

    public function createTrade(Request $request)
    {
        $data = $request->except('_token');

        $data['member_id'] = auth()->user()->id;
        $this->service->createTrade($data);

        return redirect()->back()->with('message', 'Trade action not implemented yet');
    }

    public function askQuestion(Request $request)
    {
        $data = $request->except('_token');

        $data['member_id'] = auth()->user()->id;   
        $this->service->askQuestion($data);

        return redirect()->back()->with('message', 'Question action not implemented yet');
    }

    public function answerQuestion(Request $request)
    {
        $data = $request->except('_token');

        $data['member_id'] = auth()->user()->id;
        $this->service->answerQuestion($data);

        return redirect()->back()->with('message', 'Answer action not implemented yet');
    }

    public function acceptAnswer(Request $request)
    {
        // not implemented yet
        return redirect()->back()->with('message', 'Accept answer not implemented yet');
    }

}