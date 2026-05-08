@extends('layouts.app')

@section('title', 'Marketplace — Market')

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
       class="market_nav_pill active">
        <i class="ti ti-shopping-bag"></i> Market
    </a>
    <a href="{{ route('marketplace.profile') }}"
       class="market_nav_pill">
        <i class="ti ti-user"></i> My Profile
    </a>
</nav>


{{-- Page header --}}
<div class="market_header">
    <div class="market_header_left">
        <h1 class="market_page_title">Community Market</h1>
        <p class="market_page_sub">Browse listings, exchange tips, and join canning sessions.</p>
    </div>

    <div class="market_header_actions">
        <button class="market_btn market_btn--ghost"
                onclick="openModal('modal_question')">
            <i class="ti ti-message-question"></i>
            Ask a Question
        </button>

        <button class="market_btn market_btn--primary"
                onclick="openModal('modal_listing')">
            <i class="ti ti-plus"></i>
            Post Listing
        </button>
    </div>
</div>


{{-- Surplus alert banner (Feature 35) --}}
@if($surplusAlerts->isNotEmpty())
    <div class="market_surplus_banner">
        <i class="ti ti-plant-2"></i>
        <span>Your upcoming harvests:</span>
        <div class="market_surplus_items">
            @foreach($surplusAlerts as $alert)
                <span class="market_surplus_pill">
                    {{ $alert['produce'] }} — {{ $alert['days_to_harvest'] }}d
                </span>
            @endforeach
        </div>
        <span style="margin-left:auto; font-size:11px;">
            Consider posting a listing early!
        </span>
    </div>
@endif


{{-- Main layout --}}
<div class="market_layout">

    {{-- LEFT SIDEBAR --}}
    <aside class="market_sidebar">

        {{-- Tabs --}}
        <div class="market_tabs">
            <button class="market_tab active" onclick="switchTab('listings', this)">
                <i class="ti ti-package"></i> Listings
            </button>
            <button class="market_tab" onclick="switchTab('questions', this)">
                <i class="ti ti-help"></i> Q&amp;A
            </button>
            <button class="market_tab" onclick="switchTab('canning', this)">
                <i class="ti ti-tool"></i> Canning
            </button>
        </div>

        {{-- Search --}}
        <div class="market_search_wrap">
            <i class="ti ti-search"></i>
            <input id="market_search"
                   class="market_search"
                   type="search"
                   placeholder="Search...">
        </div>

        {{-- LISTINGS TAB --}}
        <ul class="market_list" id="tab_listings">
            @forelse($listings as $listing)
                <li class="market_list_item"
                    data-item='@json($listing)'
                    data-tab="listings"
                    tabindex="0">

                    <div class="market_list_item_body">
                        <span class="market_list_item_name">
                            {{ $listing->produce_name }}
                        </span>
                        <span class="market_list_item_meta">
                            {{ $listing->quantity_kg }}kg
                            &middot; {{ $listing->user->name ?? '—' }}
                            @if($listing->allergen_flags)
                                &middot; <span style="color:var(--market_red_text)">⚠ Allergen</span>
                            @endif
                        </span>
                    </div>

                    <div style="display:flex;flex-direction:column;align-items:flex-end;gap:4px;">
                        <span class="market_badge market_badge--{{ $listing->type }}">
                            {{ ucfirst($listing->type) }}
                        </span>
                        @if($listing->type === 'flash' && $listing->expires_at)
                            <span class="market_countdown"
                                  data-expires="{{ $listing->expires_at }}">
                                --:--
                            </span>
                        @endif
                    </div>

                </li>
            @empty
                <li class="market_list_empty">No listings available.</li>
            @endforelse
        </ul>

        {{-- QUESTIONS TAB --}}
        <ul class="market_list" id="tab_questions" style="display:none;">
            @forelse($questions as $question)
                <li class="market_list_item"
                    data-item='@json($question)'
                    data-tab="questions"
                    tabindex="0">

                    <div class="market_list_item_body">
                        <span class="market_list_item_name">
                            {{ $question->title }}
                        </span>
                        <span class="market_list_item_meta">
                            {{ $question->user->name ?? '—' }}
                            &middot;
                            {{ $question->answers_count ?? count($question->answers) }} answer(s)
                        </span>
                    </div>

                    @if(count($question->answers) === 0)
                        <span class="market_badge market_badge--reserved">Unanswered</span>
                    @else
                        <span class="market_badge market_badge--available">Answered</span>
                    @endif

                </li>
            @empty
                <li class="market_list_empty">No questions yet.</li>
            @endforelse
        </ul>

        {{-- CANNING TAB --}}
        <ul class="market_list" id="tab_canning" style="display:none;">
            @forelse($canningSessions as $session)
                <li class="market_list_item"
                    data-item='@json($session)'
                    data-tab="canning"
                    tabindex="0">

                    <div class="market_list_item_body">
                        <span class="market_list_item_name">
                            {{ $session->title }}
                        </span>
                        <span class="market_list_item_meta">
                            {{ \Carbon\Carbon::parse($session->scheduled_at)->format('M d') }}
                            &middot; {{ $session->current_count }}/{{ $session->max_members }} members
                        </span>
                    </div>

                    <span class="market_badge market_badge--open">Open</span>

                </li>
            @empty
                <li class="market_list_empty">No canning sessions open.</li>
            @endforelse
        </ul>

    </aside>


    {{-- RIGHT PANEL --}}
    <div class="market_panel">

        {{-- Empty state --}}
        <div class="market_panel_empty" id="panel_empty">
            <div class="market_panel_empty_icon">
                <i class="ti ti-basket"></i>
            </div>
            <h2>Select an item</h2>
            <p>Choose a listing, question, or canning session from the sidebar.</p>
        </div>

        {{-- LISTING DETAIL --}}
        <div class="market_detail" id="panel_listing" style="display:none;">

            <div class="market_detail_header">
                <div>
                    <p class="market_section_label" style="margin-bottom:4px;">Listing Detail</p>
                    <h2 class="market_detail_title" id="l_name"></h2>
                    <p class="market_detail_sub">
                        by <span id="l_seller"></span>
                    </p>
                </div>
                <div style="display:flex;flex-direction:column;align-items:flex-end;gap:6px;">
                    <span class="market_badge" id="l_type_badge"></span>
                    <div class="market_stars" id="l_stars"></div>
                </div>
            </div>

            <div class="market_detail_body">

                {{-- Stats --}}
                <div class="market_stats_row">
                    <div class="market_stat">
                        <span class="market_stat_label">Quantity</span>
                        <span class="market_stat_value"><span id="l_qty"></span><span class="market_stat_sub">kg</span></span>
                    </div>
                    <div class="market_stat">
                        <span class="market_stat_label">Price</span>
                        <span class="market_stat_value" id="l_price"></span>
                    </div>
                    <div class="market_stat">
                        <span class="market_stat_label">Quality</span>
                        <span class="market_stat_value"><span id="l_quality"></span><span class="market_stat_sub">/5</span></span>
                    </div>
                </div>

                {{-- Description --}}
                <div>
                    <p class="market_section_label">Description</p>
                    <p id="l_desc" style="font-size:13px;color:var(--color-text-primary);line-height:1.6;margin:0;"></p>
                </div>

                {{-- Allergen flags (Feature 36) --}}
                <div id="l_allergen_wrap" style="display:none;">
                    <p class="market_section_label">⚠ Allergen Flags</p>
                    <div class="market_allergen_row" id="l_allergens"></div>
                </div>

                {{-- Flash countdown --}}
                <div id="l_flash_wrap" style="display:none;">
                    <p class="market_section_label">⚡ Flash Trade — Expires</p>
                    <span class="market_countdown" id="l_countdown" style="font-size:16px;padding:6px 14px;"></span>
                </div>

                {{-- Rate this listing (Feature 38) --}}
                <div>
                    <p class="market_section_label">Rate Quality</p>
                    <form method="POST"
                          action="{{ route('marketplace.ratings.store') }}"
                          class="market_inline_form">
                        @csrf
                        <input type="hidden" name="listing_id" id="l_rate_id">
                        <select name="score" class="market_select" style="width:auto;">
                            <option value="5">★★★★★ Excellent</option>
                            <option value="4">★★★★☆ Good</option>
                            <option value="3">★★★☆☆ Average</option>
                            <option value="2">★★☆☆☆ Poor</option>
                            <option value="1">★☆☆☆☆ Bad</option>
                        </select>
                        <input type="text"
                               name="comment"
                               class="market_input"
                               placeholder="Optional comment...">
                        <button class="market_btn market_btn--ghost">
                            Rate
                        </button>
                    </form>
                </div>

            </div>

            {{-- Footer: trade action --}}
            <div class="market_detail_footer">
                <div id="l_action_standard" style="display:none;">
                    <form method="POST"
                          action="{{ route('marketplace.trades.store') }}"
                          class="market_inline_form">
                        @csrf
                        <input type="hidden" name="listing_id" id="l_trade_id">
                        <input type="text"
                               name="note"
                               class="market_input"
                               placeholder="Message to seller (optional)">
                        <button class="market_submit">
                            <i class="ti ti-arrows-exchange"></i>
                            Request Trade
                        </button>
                    </form>
                </div>

                <div id="l_action_flash" style="display:none;">
                    <form method="POST"
                          action="{{ route('marketplace.flash.claim') }}"
                          class="market_inline_form">
                        @csrf
                        <input type="hidden" name="listing_id" id="l_flash_id">
                        <button class="market_btn market_btn--flash" style="height:40px;font-size:14px;">
                            <i class="ti ti-bolt"></i>
                            Claim Flash Trade
                        </button>
                    </form>
                </div>

                <div id="l_action_gift" style="display:none;">
                    <form method="POST"
                          action="{{ route('marketplace.trades.store') }}"
                          class="market_inline_form">
                        @csrf
                        <input type="hidden" name="listing_id" id="l_gift_id">
                        <button class="market_submit">
                            <i class="ti ti-heart"></i>
                            Claim Gift
                        </button>
                    </form>
                </div>
            </div>

        </div>

        {{-- QUESTION DETAIL --}}
        <div class="market_detail" id="panel_question" style="display:none;">

            <div class="market_detail_header">
                <div>
                    <p class="market_section_label" style="margin-bottom:4px;">Community Question</p>
                    <h2 class="market_detail_title" id="q_title"></h2>
                    <p class="market_detail_sub">
                        Asked by <span id="q_author"></span>
                    </p>
                </div>
                <span class="market_karma_badge">
                    <i class="ti ti-award"></i>
                    +{{ \App\Services\MarketPlaceService::CREDITS_PER_ANSWER }} credits for answering
                </span>
            </div>

            <div class="market_detail_body">

                <div>
                    <p class="market_section_label">Question</p>
                    <p id="q_body" style="font-size:14px;line-height:1.7;margin:0;color:var(--color-text-primary);"></p>
                </div>

                <div>
                    <p class="market_section_label">
                        Answers <span id="q_answer_count" style="font-weight:400;text-transform:none;letter-spacing:0;"></span>
                    </p>
                    <div class="market_answer_thread" id="q_answers"></div>
                </div>

            </div>

            {{-- Footer: post answer --}}
            <div class="market_detail_footer">
                <form method="POST"
                      action="{{ route('marketplace.answers.store') }}"
                      class="market_inline_form">
                    @csrf
                    <input type="hidden" name="question_id" id="q_answer_qid">
                    <input type="text"
                           name="body"
                           class="market_input"
                           placeholder="Share your gardening knowledge...">
                    <button class="market_submit">
                        <i class="ti ti-send"></i>
                        Answer
                    </button>
                </form>
            </div>

        </div>

        {{-- CANNING DETAIL --}}
        <div class="market_detail" id="panel_canning" style="display:none;">

            <div class="market_detail_header">
                <div class="market_canning_detail_header">
                    <p class="market_section_label" style="margin-bottom:4px;">Canning Session</p>
                    <h2 class="market_detail_title" id="c_title"></h2>
                    <div class="market_canning_meta" id="c_meta" style="margin-top:6px;"></div>
                </div>
                <span class="market_badge market_badge--open">Open</span>
            </div>

            <div class="market_detail_body">

                <div>
                    <p class="market_section_label">About this Session</p>
                    <p id="c_desc" style="font-size:13px;line-height:1.6;margin:0;color:var(--color-text-primary);"></p>
                </div>

                <div class="market_stats_row">
                    <div class="market_stat">
                        <span class="market_stat_label">Date</span>
                        <span class="market_stat_value" id="c_date" style="font-size:15px;"></span>
                    </div>
                    <div class="market_stat">
                        <span class="market_stat_label">Location</span>
                        <span class="market_stat_value" id="c_location" style="font-size:15px;"></span>
                    </div>
                    <div class="market_stat">
                        <span class="market_stat_label">Spots Left</span>
                        <span class="market_stat_value" id="c_spots"></span>
                    </div>
                </div>

                <div>
                    <p class="market_section_label">Produce Target</p>
                    <p id="c_target" style="font-size:13px;color:var(--color-text-primary);margin:0;"></p>
                </div>

                <div>
                    <p class="market_section_label">Members Joining</p>
                    <div class="market_capacity_bar">
                        <div class="market_capacity_fill" id="c_bar"></div>
                    </div>
                    <div class="market_contributors_list" id="c_contributors" style="margin-top:10px;"></div>
                </div>

            </div>

            {{-- Footer: join --}}
            <div class="market_detail_footer">
                <form method="POST"
                      action="{{ route('marketplace.canning.join') }}"
                      class="market_inline_form">
                    @csrf
                    <input type="hidden" name="session_id" id="c_join_id">
                    <input type="text"
                           name="produce_name"
                           class="market_input"
                           placeholder="What will you bring?">
                    <input type="number"
                           name="quantity_kg"
                           class="market_input"
                           style="width:90px;"
                           min="0.1"
                           step="0.1"
                           placeholder="kg">
                    <button class="market_submit">
                        <i class="ti ti-users-plus"></i>
                        Join
                    </button>
                </form>
            </div>

        </div>

    </div>
</div>


{{-- ══ MODAL: Post Listing ══════════════════════════════════ --}}
<div id="modal_listing" class="market_modal" style="display:none;">
    <div class="market_modal_box">

        <div class="market_modal_header">
            <h3 class="market_modal_title">Post a Listing</h3>
            <button class="market_modal_close" onclick="closeModal('modal_listing')">
                <i class="ti ti-x"></i>
            </button>
        </div>

        <div class="market_modal_body">
            <form method="POST"
                  action="{{ route('marketplace.listings.store') }}"
                  class="market_form"
                  id="listing_form">
                @csrf

                <div class="market_field">
                    <label class="market_label">Listing Type</label>
                    <div class="market_radio_group">

                        <label class="market_radio_option">
                            <input type="radio" name="type" value="standard" checked>
                            <div class="market_radio_card">
                                <span class="market_radio_card_title">Standard</span>
                                <span class="market_radio_card_desc">Set your price</span>
                            </div>
                        </label>

                        <label class="market_radio_option market_radio_option--flash">
                            <input type="radio" name="type" value="flash" id="type_flash">
                            <div class="market_radio_card">
                                <span class="market_radio_card_title">⚡ Flash</span>
                                <span class="market_radio_card_desc">Pickup urgently</span>
                            </div>
                        </label>

                        <label class="market_radio_option market_radio_option--gift">
                            <input type="radio" name="type" value="gift">
                            <div class="market_radio_card">
                                <span class="market_radio_card_title">🌱 Gift</span>
                                <span class="market_radio_card_desc">Free + Karma</span>
                            </div>
                        </label>

                    </div>
                </div>

                <div class="market_field_row">
                    <div class="market_field">
                        <label class="market_label">Produce Name</label>
                        <input type="text"
                               name="produce_name"
                               class="market_input"
                               placeholder="e.g. Cherry Tomatoes"
                               required>
                    </div>
                    <div class="market_field">
                        <label class="market_label">Quantity (kg)</label>
                        <input type="number"
                               name="quantity_kg"
                               class="market_input"
                               min="0.1"
                               step="0.1"
                               placeholder="e.g. 2.5"
                               required>
                    </div>
                </div>

                <div class="market_field_row">
                    <div class="market_field" id="price_field">
                        <label class="market_label">Price</label>
                        <input type="number"
                               name="price"
                               class="market_input"
                               min="0"
                               step="0.01"
                               placeholder="0.00">
                    </div>
                    <div class="market_field" id="flash_field" style="display:none;">
                        <label class="market_label">Pickup Window (hours)</label>
                        <input type="number"
                               name="pickup_window_hours"
                               class="market_input"
                               min="1"
                               max="24"
                               placeholder="e.g. 2">
                    </div>
                </div>

                <div class="market_field">
                    <label class="market_label">Description</label>
                    <textarea name="description"
                              class="market_textarea"
                              placeholder="Describe condition, pickup details, etc."></textarea>
                </div>

                <div class="market_modal_footer" style="padding:0;border:none;justify-content:flex-start;">
                    <button type="submit" class="market_submit">
                        <i class="ti ti-upload"></i>
                        Post Listing
                    </button>
                    <button type="button"
                            class="market_btn market_btn--ghost"
                            onclick="closeModal('modal_listing')">
                        Cancel
                    </button>
                </div>

            </form>
        </div>

    </div>
</div>


{{-- ══ MODAL: Ask Question ════════════════════════════════ --}}
<div id="modal_question" class="market_modal" style="display:none;">
    <div class="market_modal_box">

        <div class="market_modal_header">
            <h3 class="market_modal_title">Ask the Community</h3>
            <button class="market_modal_close" onclick="closeModal('modal_question')">
                <i class="ti ti-x"></i>
            </button>
        </div>

        <div class="market_modal_body">
            <form method="POST"
                  action="{{ route('marketplace.questions.store') }}"
                  class="market_form">
                @csrf

                <div class="market_field">
                    <label class="market_label">Question Title</label>
                    <input type="text"
                           name="title"
                           class="market_input"
                           placeholder="e.g. How do I prevent blossom end rot?"
                           required>
                </div>

                <div class="market_field">
                    <label class="market_label">Details</label>
                    <textarea name="body"
                              class="market_textarea"
                              rows="4"
                              placeholder="Give as much context as possible..."
                              required></textarea>
                </div>

                <div class="market_field">
                    <label class="market_label">Tags (optional)</label>
                    <input type="text"
                           name="tags"
                           class="market_input"
                           placeholder="e.g. tomatoes, watering, soil">
                </div>

                <div class="market_modal_footer" style="padding:0;border:none;justify-content:flex-start;">
                    <button type="submit" class="market_submit">
                        <i class="ti ti-help"></i>
                        Post Question
                    </button>
                    <button type="button"
                            class="market_btn market_btn--ghost"
                            onclick="closeModal('modal_question')">
                        Cancel
                    </button>
                </div>

            </form>
        </div>

    </div>
</div>


{{-- ══ MODAL: Create Canning Session ═════════════════════ --}}
<div id="modal_canning" class="market_modal" style="display:none;">
    <div class="market_modal_box">

        <div class="market_modal_header">
            <h3 class="market_modal_title">Create Canning Session 🫙</h3>
            <button class="market_modal_close" onclick="closeModal('modal_canning')">
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
                    <input type="text"
                           name="title"
                           class="market_input"
                           placeholder="e.g. Tomato Sauce Day"
                           required>
                </div>

                <div class="market_field_row">
                    <div class="market_field">
                        <label class="market_label">Date</label>
                        <input type="date"
                               name="scheduled_at"
                               class="market_input"
                               required>
                    </div>
                    <div class="market_field">
                        <label class="market_label">Max Members</label>
                        <input type="number"
                               name="max_members"
                               class="market_input"
                               min="2"
                               max="30"
                               placeholder="e.g. 8"
                               required>
                    </div>
                </div>

                <div class="market_field">
                    <label class="market_label">Location</label>
                    <input type="text"
                           name="location"
                           class="market_input"
                           placeholder="e.g. Community Hall, Plot 4"
                           required>
                </div>

                <div class="market_field">
                    <label class="market_label">Produce Target</label>
                    <input type="text"
                           name="produce_target"
                           class="market_input"
                           placeholder="e.g. 20kg tomatoes → 40 jars of sauce"
                           required>
                </div>

                <div class="market_field">
                    <label class="market_label">Description</label>
                    <textarea name="description"
                              class="market_textarea"
                              placeholder="What will we make? How will jars be split?"></textarea>
                </div>

                <div class="market_modal_footer" style="padding:0;border:none;justify-content:flex-start;">
                    <button type="submit" class="market_submit">
                        <i class="ti ti-tool"></i>
                        Create Session
                    </button>
                    <button type="button"
                            class="market_btn market_btn--ghost"
                            onclick="closeModal('modal_canning')">
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
/* ── Tab switching ─────────────────────────────────────── */
let currentTab = 'listings';

function switchTab(tab, btn) {
    currentTab = tab;

    document.querySelectorAll('.market_tab').forEach(t => t.classList.remove('active'));
    btn.classList.add('active');

    ['listings', 'questions', 'canning'].forEach(t => {
        document.getElementById('tab_' + t).style.display = t === tab ? '' : 'none';
    });

    clearPanel();
}

/* ── Panel helpers ─────────────────────────────────────── */
function clearPanel() {
    document.getElementById('panel_empty').style.display    = '';
    document.getElementById('panel_listing').style.display  = 'none';
    document.getElementById('panel_question').style.display = 'none';
    document.getElementById('panel_canning').style.display  = 'none';
}

function showPanel(id) {
    clearPanel();
    document.getElementById('panel_empty').style.display = 'none';
    document.getElementById(id).style.display = '';
}

/* ── List item click ───────────────────────────────────── */
document.querySelectorAll('.market_list_item').forEach(el => {
    el.addEventListener('click', () => {

        document.querySelectorAll('.market_list_item').forEach(i => i.classList.remove('active'));
        el.classList.add('active');

        const item = JSON.parse(el.dataset.item);
        const tab  = el.dataset.tab;

        if (tab === 'listings')  populateListing(item);
        if (tab === 'questions') populateQuestion(item);
        if (tab === 'canning')   populateCanning(item);
    });
});

/* ── Listing detail ────────────────────────────────────── */
function populateListing(l) {
    showPanel('panel_listing');

    document.getElementById('l_name').textContent   = l.produce_name;
    document.getElementById('l_seller').textContent = l.user?.name ?? '—';
    document.getElementById('l_qty').textContent    = l.quantity_kg;
    document.getElementById('l_desc').textContent   = l.description ?? 'No description provided.';
    document.getElementById('l_quality').textContent = l.quality_score ?? '—';
    document.getElementById('l_rate_id').value      = l.id;
    document.getElementById('l_trade_id').value     = l.id;
    document.getElementById('l_flash_id').value     = l.id;
    document.getElementById('l_gift_id').value      = l.id;

    // Type badge
    const typeBadge = document.getElementById('l_type_badge');
    const typeLabels = { standard: 'Standard', flash: '⚡ Flash', gift: '🌱 Gift' };
    typeBadge.textContent = typeLabels[l.type] || l.type;
    typeBadge.className   = 'market_badge market_badge--' + l.type;

    // Price
    document.getElementById('l_price').textContent =
        l.type === 'gift' ? 'Free' : (l.price ? '£' + parseFloat(l.price).toFixed(2) : '—');

    // Stars
    const score = Math.round(l.quality_score ?? 0);
    document.getElementById('l_stars').innerHTML =
        Array.from({length: 5}, (_, i) =>
            `<i class="ti ti-star${i < score ? '-filled' : ''}"></i>`
        ).join('');

    // Allergens
    const allergenWrap = document.getElementById('l_allergen_wrap');
    const allergenRow  = document.getElementById('l_allergens');
    if (l.allergen_flags) {
        allergenWrap.style.display = '';
        allergenRow.innerHTML = l.allergen_flags.split(',').map(a =>
            `<span class="market_allergen_badge"><i class="ti ti-alert-triangle"></i>${a.trim()}</span>`
        ).join('');
    } else {
        allergenWrap.style.display = 'none';
    }

    // Flash expiry
    const flashWrap = document.getElementById('l_flash_wrap');
    if (l.type === 'flash' && l.expires_at) {
        flashWrap.style.display = '';
        startCountdown(document.getElementById('l_countdown'), l.expires_at);
    } else {
        flashWrap.style.display = 'none';
    }

    // Action buttons
    document.getElementById('l_action_standard').style.display = l.type === 'standard' ? '' : 'none';
    document.getElementById('l_action_flash').style.display    = l.type === 'flash'    ? '' : 'none';
    document.getElementById('l_action_gift').style.display     = l.type === 'gift'     ? '' : 'none';
}

/* ── Question detail ───────────────────────────────────── */
function populateQuestion(q) {
    showPanel('panel_question');

    document.getElementById('q_title').textContent    = q.title;
    document.getElementById('q_author').textContent   = q.user?.name ?? '—';
    document.getElementById('q_body').textContent     = q.body;
    document.getElementById('q_answer_qid').value     = q.id;

    const answers = q.answers ?? [];
    document.getElementById('q_answer_count').textContent = `(${answers.length})`;

    document.getElementById('q_answers').innerHTML = answers.length
        ? answers.map(a => `
            <div class="market_answer_item">
                <div class="market_answer_item_header">
                    <span class="market_answer_author">${a.user?.name ?? 'Member'}</span>
                    <span class="market_answer_date">${formatDate(a.created_at)}</span>
                </div>
                <p class="market_answer_body">${escHtml(a.body)}</p>
            </div>`).join('')
        : '<p style="font-size:13px;color:var(--color-text-secondary);">No answers yet. Be the first!</p>';
}

/* ── Canning detail ────────────────────────────────────── */
function populateCanning(s) {
    showPanel('panel_canning');

    document.getElementById('c_title').textContent    = s.title;
    document.getElementById('c_desc').textContent     = s.description ?? '';
    document.getElementById('c_date').textContent     = formatDate(s.scheduled_at);
    document.getElementById('c_location').textContent = s.location;
    document.getElementById('c_target').textContent   = s.produce_target;
    document.getElementById('c_join_id').value        = s.id;

    const spots = s.max_members - s.current_count;
    document.getElementById('c_spots').textContent = spots > 0 ? spots : 'Full';

    // Capacity bar
    const pct = Math.round((s.current_count / s.max_members) * 100);
    document.getElementById('c_bar').style.width = pct + '%';

    // Contributors
    const contributors = s.contributors ?? [];
    document.getElementById('c_contributors').innerHTML = contributors.length
        ? contributors.map(c => `
            <div class="market_contributor_row">
                <span class="market_contributor_name">${c.user?.name ?? 'Member'}</span>
                <span class="market_contributor_produce">${c.produce_name} — ${c.quantity_kg}kg</span>
            </div>`).join('')
        : '<p style="font-size:13px;color:var(--color-text-secondary);margin:0;">No members yet.</p>';
}


/* ── Flash countdown timer ─────────────────────────────── */
const countdownIntervals = [];

function startCountdown(el, expiresAt) {
    countdownIntervals.forEach(clearInterval);
    countdownIntervals.length = 0;

    function tick() {
        const diff = new Date(expiresAt) - new Date();
        if (diff <= 0) {
            el.textContent = 'Expired';
            return;
        }
        const h = Math.floor(diff / 3600000);
        const m = Math.floor((diff % 3600000) / 60000);
        const s = Math.floor((diff % 60000) / 1000);
        el.textContent = `${pad(h)}:${pad(m)}:${pad(s)}`;
    }

    tick();
    countdownIntervals.push(setInterval(tick, 1000));
}

// Also run countdowns on list items
document.querySelectorAll('.market_countdown[data-expires]').forEach(el => {
    startCountdown(el, el.dataset.expires);
});


/* ── Listing form: show/hide flash field ───────────────── */
document.querySelectorAll('input[name="type"]').forEach(radio => {
    radio.addEventListener('change', () => {
        const isFlash = radio.value === 'flash';
        const isGift  = radio.value === 'gift';
        document.getElementById('flash_field').style.display = isFlash ? '' : 'none';
        document.getElementById('price_field').style.display = isGift  ? 'none' : '';
    });
});


/* ── Search ─────────────────────────────────────────────── */
document.getElementById('market_search').addEventListener('input', (e) => {
    const q = e.target.value.toLowerCase();
    const activeList = document.getElementById('tab_' + currentTab);
    activeList.querySelectorAll('.market_list_item').forEach(el => {
        el.style.display = el.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});


/* ── Modals ─────────────────────────────────────────────── */
function openModal(id)  { document.getElementById(id).style.display = 'flex'; }
function closeModal(id) { document.getElementById(id).style.display = 'none'; }

document.querySelectorAll('.market_modal').forEach(modal => {
    modal.addEventListener('click', (e) => {
        if (e.target === modal) modal.style.display = 'none';
    });
});


/* ── Canning session: open modal from sidebar header ───── */
// Optionally trigger via a button you can add to the canning tab header


/* ── Helpers ────────────────────────────────────────────── */
function pad(n) { return String(n).padStart(2, '0'); }

function formatDate(str) {
    if (!str) return '—';
    return new Date(str).toLocaleDateString('en-GB', { day:'numeric', month:'short', year:'numeric' });
}

function escHtml(str) {
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;');
}
</script>
@endpush