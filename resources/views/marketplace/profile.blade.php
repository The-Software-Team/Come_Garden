@extends('layouts.app')

@section('title', 'Marketplace — My Profile')

@push('styles')
    @vite(['resources/css/domain/marketplace.css'])
@endpush

@section('content')

{{-- Alerts --}}
@if(session('message'))
    <div class="market_alert market_alert--success">
        <i class="ti ti-circle-check"></i>
        <span>{{ session('message') }}</span>
    </div>
@endif

@if(session('error') || $errors->any())
    <div class="market_alert market_alert--warning">
        <i class="ti ti-alert-circle"></i>
        <span>{{ session('error') ?? $errors->first() }}</span>
    </div>
@endif


{{-- Nav pills --}}
<nav class="market_nav_pills">
    <a href="{{ route('marketplace.market') }}"
       class="market_nav_pill">
        <i class="ti ti-shopping-bag"></i> Market
    </a>
    <a href="{{ route('marketplace.profile') }}"
       class="market_nav_pill active">
        <i class="ti ti-user"></i> My Profile
    </a>
</nav>


{{-- Page header --}}
<div class="market_header">
    <div class="market_header_left">
        <h1 class="market_page_title">My Marketplace</h1>
        <p class="market_page_sub">Track your listings, trades, questions, answers, and canning sessions.</p>
    </div>
</div>


{{-- Stats bar --}}
<div class="market_profile_stats">

    <div class="market_profile_stat market_profile_stat--karma">
        <span class="market_profile_stat_label">Karma Points</span>
        <span class="market_profile_stat_value">{{ $karma }}</span>
        <span class="market_profile_stat_sub">From gifts &amp; contributions</span>
    </div>

    <div class="market_profile_stat market_profile_stat--credits">
        <span class="market_profile_stat_label">Seed Bank Credits</span>
        <span class="market_profile_stat_value">{{ auth()->user()->seedbank_credits ?? 0 }}</span>
        <span class="market_profile_stat_sub">Earned by answering questions</span>
    </div>

    <div class="market_profile_stat market_profile_stat--quality">
        <span class="market_profile_stat_label">Quality Score</span>
        <span class="market_profile_stat_value">
            {{ $qualityScore ? number_format($qualityScore, 1) : '—' }}
        </span>
        <span class="market_profile_stat_sub">Avg. rating on your listings</span>
    </div>

    <div class="market_profile_stat">
        <span class="market_profile_stat_label">Active Listings</span>
        <span class="market_profile_stat_value">
            {{ $myListings->where('status', 'available')->count() }}
        </span>
        <span class="market_profile_stat_sub">of {{ $myListings->count() }} total</span>
    </div>

</div>


{{-- Main layout --}}
<div class="market_layout">

    {{-- LEFT SIDEBAR — vertical profile tabs --}}
    <aside class="market_sidebar" style="flex-direction:row;min-height:unset;">

        <div class="market_profile_tabs" style="width:100%;flex-direction:column;">

            <p class="market_section_label" style="padding:10px 12px 4px;margin:0;">My Activity</p>

            <button class="market_profile_tab active"
                    onclick="switchProfileTab('listings', this)">
                <i class="ti ti-package"></i>
                Listings
                <span class="market_profile_tab_count">{{ $myListings->count() }}</span>
            </button>

            <button class="market_profile_tab"
                    onclick="switchProfileTab('trades', this)">
                <i class="ti ti-arrows-exchange"></i>
                Trades
                <span class="market_profile_tab_count">{{ $myTrades->count() }}</span>
            </button>

            <button class="market_profile_tab"
                    onclick="switchProfileTab('questions', this)">
                <i class="ti ti-help"></i>
                My Questions
                <span class="market_profile_tab_count">{{ $myQuestions->count() }}</span>
            </button>

            <button class="market_profile_tab"
                    onclick="switchProfileTab('answers', this)">
                <i class="ti ti-message-check"></i>
                My Answers
                <span class="market_profile_tab_count">{{ $myAnswers->count() }}</span>
            </button>

            <button class="market_profile_tab"
                    onclick="switchProfileTab('canning', this)">
                <i class="ti ti-tool"></i>
                Canning
                <span class="market_profile_tab_count">{{ $myOrganised->count() + $myJoined->count() }}</span>
            </button>

            <div style="margin-top:auto;padding:12px 8px;border-top:0.5px solid var(--color-border-tertiary);">
                <p class="market_section_label" style="padding:0 4px;">Karma Log</p>
                @forelse($karmaLog as $entry)
                    <div style="padding:6px 4px;border-bottom:0.5px solid var(--color-border-tertiary);font-size:12px;color:var(--color-text-secondary);">
                        <span style="color:var(--market_purple_text);font-weight:600;">+{{ $entry->points }}</span>
                        {{ $entry->description }}
                    </div>
                @empty
                    <p style="font-size:12px;color:var(--color-text-tertiary);padding:0 4px;">No karma yet.</p>
                @endforelse
            </div>

        </div>

    </aside>


    {{-- RIGHT PANEL --}}
    <div class="market_panel">

        {{-- ══ MY LISTINGS ══════════════════════════════ --}}
        <div id="ptab_listings" class="market_detail" style="height:100%;">

            <div class="market_detail_header">
                <div>
                    <p class="market_section_label" style="margin-bottom:4px;">My Listings</p>
                    <h2 class="market_detail_title">Posted Produce</h2>
                </div>
            </div>

            <div class="market_detail_body">
                @forelse($myListings as $listing)
                    <div class="market_item_card">

                        <div class="market_item_card_top">
                            <div>
                                <h4 class="market_item_card_title">{{ $listing->produce_name }}</h4>
                                <p class="market_item_card_sub">
                                    {{ $listing->quantity_kg }}kg
                                    &middot; Posted {{ $listing->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <div style="display:flex;flex-direction:column;align-items:flex-end;gap:5px;">
                                <span class="market_badge market_badge--{{ $listing->status }}">
                                    {{ ucfirst($listing->status) }}
                                </span>
                                <span class="market_badge market_badge--{{ $listing->type }}">
                                    {{ ucfirst($listing->type) }}
                                </span>
                            </div>
                        </div>

                        <div class="market_item_card_body">
                            <div class="market_item_card_meta">

                                <span class="market_item_card_meta_item">
                                    <i class="ti ti-coins"></i>
                                    {{ $listing->type === 'gift' ? 'Free' : '£' . number_format($listing->price ?? 0, 2) }}
                                </span>

                                @if($listing->quality_score)
                                    <span class="market_item_card_meta_item">
                                        <i class="ti ti-star-filled" style="color:#d4a017;"></i>
                                        {{ number_format($listing->quality_score, 1) }}/5
                                    </span>
                                @endif

                                @if($listing->allergen_flags)
                                    <span class="market_item_card_meta_item" style="color:var(--market_red_text);">
                                        <i class="ti ti-alert-triangle"></i>
                                        {{ $listing->allergen_flags }}
                                    </span>
                                @endif

                                @if($listing->type === 'flash' && $listing->expires_at)
                                    <span class="market_item_card_meta_item">
                                        <i class="ti ti-clock"></i>
                                        Expires {{ \Carbon\Carbon::parse($listing->expires_at)->diffForHumans() }}
                                    </span>
                                @endif

                                <span class="market_item_card_meta_item">
                                    <i class="ti ti-arrows-exchange"></i>
                                    {{ $listing->trades->count() }} trade request(s)
                                </span>

                            </div>
                        </div>

                    </div>
                @empty
                    <div class="market_panel_empty" style="flex:1;">
                        <div class="market_panel_empty_icon">
                            <i class="ti ti-package-off"></i>
                        </div>
                        <h2>No listings yet</h2>
                        <p>Head to the Market to post your first listing.</p>
                        <a href="{{ route('marketplace.market') }}" class="market_btn market_btn--primary">
                            Go to Market
                        </a>
                    </div>
                @endforelse
            </div>

        </div>


        {{-- ══ MY TRADES ════════════════════════════════ --}}
        <div id="ptab_trades" class="market_detail" style="display:none;height:100%;">

            <div class="market_detail_header">
                <div>
                    <p class="market_section_label" style="margin-bottom:4px;">My Trades</p>
                    <h2 class="market_detail_title">Trade Activity</h2>
                </div>
            </div>

            <div class="market_detail_body">
                @forelse($myTrades as $trade)
                    <div class="market_item_card">

                        <div class="market_item_card_top">
                            <div>
                                <h4 class="market_item_card_title">
                                    {{ $trade->listing->produce_name ?? 'Unknown Listing' }}
                                </h4>
                                <p class="market_item_card_sub">
                                    @if($trade->buyer_id === auth()->id())
                                        You requested from {{ $trade->seller->name ?? '—' }}
                                    @else
                                        {{ $trade->buyer->name ?? '—' }} requested from you
                                    @endif
                                </p>
                            </div>
                            <span class="market_badge market_badge--{{ $trade->status }}">
                                {{ ucfirst($trade->status) }}
                            </span>
                        </div>

                        <div class="market_item_card_body">
                            <div class="market_item_card_meta">

                                <span class="market_item_card_meta_item">
                                    <i class="ti ti-weight"></i>
                                    {{ $trade->quantity }}kg
                                </span>

                                <span class="market_item_card_meta_item">
                                    <i class="ti ti-calendar"></i>
                                    {{ $trade->created_at->diffForHumans() }}
                                </span>

                                @if($trade->note)
                                    <span class="market_item_card_meta_item">
                                        <i class="ti ti-message"></i>
                                        {{ Str::limit($trade->note, 60) }}
                                    </span>
                                @endif

                            </div>
                        </div>

                    </div>
                @empty
                    <div class="market_panel_empty" style="flex:1;">
                        <div class="market_panel_empty_icon">
                            <i class="ti ti-arrows-exchange-2"></i>
                        </div>
                        <h2>No trades yet</h2>
                        <p>Browse the Market and request a trade.</p>
                    </div>
                @endforelse
            </div>

        </div>


        {{-- ══ MY QUESTIONS ════════════════════════════ --}}
        <div id="ptab_questions" class="market_detail" style="display:none;height:100%;">

            <div class="market_detail_header">
                <div>
                    <p class="market_section_label" style="margin-bottom:4px;">My Questions</p>
                    <h2 class="market_detail_title">Community Q&amp;A</h2>
                </div>
            </div>

            <div class="market_detail_body" id="profile_questions_list">

                @forelse($myQuestions as $question)
                    <div class="market_item_card"
                         style="cursor:pointer;"
                         onclick="expandQuestion(this)"
                         data-question='@json($question)'>

                        <div class="market_item_card_top">
                            <div>
                                <h4 class="market_item_card_title">{{ $question->title }}</h4>
                                <p class="market_item_card_sub">
                                    Asked {{ $question->created_at->diffForHumans() }}
                                </p>
                            </div>
                            @if($question->answers->isEmpty())
                                <span class="market_badge market_badge--reserved">Unanswered</span>
                            @else
                                <span class="market_badge market_badge--available">
                                    {{ $question->answers->count() }} answer(s)
                                </span>
                            @endif
                        </div>

                        {{-- Expanded answers (hidden by default) --}}
                        <div class="question_answers_expand" style="display:none;">
                            <div style="padding:12px 16px;border-top:0.5px solid var(--color-border-tertiary);">
                                <p style="font-size:13px;color:var(--color-text-primary);line-height:1.6;margin:0 0 12px;">
                                    {{ $question->body }}
                                </p>
                                @if($question->answers->isNotEmpty())
                                    <p class="market_section_label">Answers</p>
                                    <div class="market_answer_thread">
                                        @foreach($question->answers as $answer)
                                            <div class="market_answer_item">
                                                <div class="market_answer_item_header">
                                                    <span class="market_answer_author">
                                                        {{ $answer->user->name ?? 'Member' }}
                                                    </span>
                                                    <span class="market_answer_date">
                                                        {{ $answer->created_at->diffForHumans() }}
                                                    </span>
                                                </div>
                                                <p class="market_answer_body">{{ $answer->body }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p style="font-size:13px;color:var(--color-text-secondary);">
                                        No answers yet.
                                    </p>
                                @endif
                            </div>
                        </div>

                    </div>
                @empty
                    <div class="market_panel_empty" style="flex:1;">
                        <div class="market_panel_empty_icon">
                            <i class="ti ti-help-off"></i>
                        </div>
                        <h2>No questions yet</h2>
                        <p>Ask the community something in the Market.</p>
                    </div>
                @endforelse

            </div>

        </div>


        {{-- ══ MY ANSWERS ══════════════════════════════ --}}
        <div id="ptab_answers" class="market_detail" style="display:none;height:100%;">

            <div class="market_detail_header">
                <div>
                    <p class="market_section_label" style="margin-bottom:4px;">My Answers</p>
                    <h2 class="market_detail_title">Tips I've Shared</h2>
                </div>
                <span class="market_karma_badge">
                    <i class="ti ti-award"></i>
                    {{ $myAnswers->count() * \App\Services\MarketPlaceService::CREDITS_PER_ANSWER }} credits earned
                </span>
            </div>

            <div class="market_detail_body">
                @forelse($myAnswers as $answer)
                    <div class="market_item_card">

                        <div class="market_item_card_top">
                            <div>
                                <h4 class="market_item_card_title">
                                    {{ $answer->question->title ?? 'Unknown Question' }}
                                </h4>
                                <p class="market_item_card_sub">
                                    Answered {{ $answer->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <span class="market_badge market_badge--open" style="background:var(--market_purple_bg);color:var(--market_purple_text);">
                                +{{ \App\Services\MarketPlaceService::CREDITS_PER_ANSWER }} credits
                            </span>
                        </div>

                        <div class="market_item_card_body">
                            <p style="font-size:13px;color:var(--color-text-primary);line-height:1.6;margin:0;">
                                {{ $answer->body }}
                            </p>
                        </div>

                    </div>
                @empty
                    <div class="market_panel_empty" style="flex:1;">
                        <div class="market_panel_empty_icon">
                            <i class="ti ti-message-off"></i>
                        </div>
                        <h2>No answers yet</h2>
                        <p>Answer questions in the Market and earn Seed Bank Credits.</p>
                    </div>
                @endforelse
            </div>

        </div>


        {{-- ══ MY CANNING ══════════════════════════════ --}}
        <div id="ptab_canning" class="market_detail" style="display:none;height:100%;">

            <div class="market_detail_header">
                <div>
                    <p class="market_section_label" style="margin-bottom:4px;">My Canning</p>
                    <h2 class="market_detail_title">Preservation Sessions</h2>
                </div>
                <button class="market_btn market_btn--primary"
                        onclick="openCanningModal()">
                    <i class="ti ti-plus"></i>
                    New Session
                </button>
            </div>

            <div class="market_detail_body">

                @if($myOrganised->isNotEmpty())
                    <div>
                        <p class="market_section_label">Sessions I'm Organising</p>
                        @foreach($myOrganised as $session)
                            <div class="market_item_card" style="margin-bottom:10px;">

                                <div class="market_item_card_top">
                                    <div>
                                        <h4 class="market_item_card_title">{{ $session->title }}</h4>
                                        <p class="market_item_card_sub">
                                            {{ \Carbon\Carbon::parse($session->session_date)->format('d M Y') }}
                                            &middot; {{ $session->location }}
                                        </p>
                                    </div>
                                    <span class="market_badge market_badge--{{ $session->status }}">
                                        {{ ucfirst($session->status) }}
                                    </span>
                                </div>

                                <div class="market_item_card_body">
                                    <div class="market_canning_meta">
                                        <span class="market_canning_meta_item">
                                            <i class="ti ti-users"></i>
                                            {{ $session->current_count }}/{{ $session->max_members }} members
                                        </span>
                                        <span class="market_canning_meta_item">
                                            <i class="ti ti-target"></i>
                                            {{ $session->produce_target }}
                                        </span>
                                    </div>

                                    <div class="market_capacity_bar">
                                        <div class="market_capacity_fill"
                                             style="width:{{ $session->max_members > 0 ? round(($session->current_count / $session->max_members) * 100) : 0 }}%">
                                        </div>
                                    </div>

                                    @if($session->contributors->isNotEmpty())
                                        <div class="market_contributors_list" style="margin-top:10px;">
                                            @foreach($session->contributors as $c)
                                                <div class="market_contributor_row">
                                                    <span class="market_contributor_name">
                                                        {{ $c->user->name ?? 'Member' }}
                                                    </span>
                                                    <span class="market_contributor_produce">
                                                        {{ $c->produce_name }} — {{ $c->quantity_kg }}kg
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                            </div>
                        @endforeach
                    </div>
                @endif

                @if($myJoined->isNotEmpty())
                    <div>
                        <p class="market_section_label">Sessions I've Joined</p>
                        @foreach($myJoined as $session)
                            <div class="market_item_card" style="margin-bottom:10px;">

                                <div class="market_item_card_top">
                                    <div>
                                        <h4 class="market_item_card_title">{{ $session->title }}</h4>
                                        <p class="market_item_card_sub">
                                            Organised by {{ $session->organizer->name ?? '—' }}
                                            &middot; {{ \Carbon\Carbon::parse($session->session_date)->format('d M Y') }}
                                        </p>
                                    </div>
                                    <span class="market_badge market_badge--{{ $session->status }}">
                                        {{ ucfirst($session->status) }}
                                    </span>
                                </div>

                                <div class="market_item_card_body">
                                    <div class="market_canning_meta">
                                        <span class="market_canning_meta_item">
                                            <i class="ti ti-map-pin"></i>
                                            {{ $session->location }}
                                        </span>
                                        <span class="market_canning_meta_item">
                                            <i class="ti ti-users"></i>
                                            {{ $session->current_count }}/{{ $session->max_members }} members
                                        </span>
                                    </div>
                                </div>

                            </div>
                        @endforeach
                    </div>
                @endif

                @if($myOrganised->isEmpty() && $myJoined->isEmpty())
                    <div class="market_panel_empty" style="flex:1;">
                        <div class="market_panel_empty_icon">
                            <i class="ti ti-tool"></i>
                        </div>
                        <h2>No canning sessions</h2>
                        <p>Create or join a group canning session in the Market.</p>
                    </div>
                @endif

            </div>

        </div>

    </div>

</div>


{{-- ══ MODAL: Create Canning Session (profile shortcut) ══ --}}
<div id="modal_canning_profile" class="market_modal" style="display:none;">
    <div class="market_modal_box">

        <div class="market_modal_header">
            <h3 class="market_modal_title">Create Canning Session 🫙</h3>
            <button class="market_modal_close" onclick="closeModal('modal_canning_profile')">
                <i class="ti ti-x"></i>
            </button>
        </div>

        <div class="market_modal_body">
            <form method="POST"
                  action="{{ route('marketplace.canning.store') }}"
                  class="market_form">
                @csrf

                <div class="market_field">
                    <label class="market_label">Session Title</label>
                    <input type="text" name="title" class="market_input"
                           placeholder="e.g. Apple Jam Day" required>
                </div>

                <div class="market_field_row">
                    <div class="market_field">
                        <label class="market_label">Date</label>
                        <input type="date" name="scheduled_at" class="market_input" required>
                    </div>
                    <div class="market_field">
                        <label class="market_label">Max Members</label>
                        <input type="number" name="max_members" class="market_input"
                               min="2" max="30" placeholder="8" required>
                    </div>
                </div>

                <div class="market_field">
                    <label class="market_label">Location</label>
                    <input type="text" name="location" class="market_input"
                           placeholder="e.g. Community Hall" required>
                </div>

                <div class="market_field">
                    <label class="market_label">Produce Target</label>
                    <input type="text" name="produce_target" class="market_input"
                           placeholder="e.g. 15kg apples → 30 jars of jam" required>
                </div>

                <div class="market_field">
                    <label class="market_label">Description</label>
                    <textarea name="description" class="market_textarea"
                              placeholder="What will we make? How are jars split?"></textarea>
                </div>

                <div class="market_modal_footer" style="padding:0;border:none;justify-content:flex-start;">
                    <button type="submit" class="market_submit">
                        <i class="ti ti-tool"></i>
                        Create Session
                    </button>
                    <button type="button" class="market_btn market_btn--ghost"
                            onclick="closeModal('modal_canning_profile')">
                        Cancel
                    </button>
                </div>

            </form>
        </div>

    </div>
</div>

@endsection


@push('scripts')
<script>
/* ── Profile tab switching ─────────────────────────────── */
function switchProfileTab(tab, btn) {
    document.querySelectorAll('.market_profile_tab').forEach(t => t.classList.remove('active'));
    btn.classList.add('active');

    ['listings', 'trades', 'questions', 'answers', 'canning'].forEach(t => {
        const el = document.getElementById('ptab_' + t);
        if (el) el.style.display = t === tab ? '' : 'none';
    });
}

/* ── Question expand/collapse ──────────────────────────── */
function expandQuestion(el) {
    const expand = el.querySelector('.question_answers_expand');
    if (!expand) return;
    expand.style.display = expand.style.display === 'none' ? '' : 'none';
}

/* ── Modals ─────────────────────────────────────────────── */
function openModal(id)  { document.getElementById(id).style.display = 'flex'; }
function closeModal(id) { document.getElementById(id).style.display = 'none'; }

function openCanningModal() {
    openModal('modal_canning_profile');
}

document.querySelectorAll('.market_modal').forEach(modal => {
    modal.addEventListener('click', e => {
        if (e.target === modal) modal.style.display = 'none';
    });
});
</script>
@endpush