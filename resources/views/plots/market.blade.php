@extends('layouts.app')
 
@section('title', 'Plot Market')
 
@push('styles')
    @vite(['resources/css/domain/plots.css'])
@endpush
 
@section('content')
 
{{-- Alerts --}}
@if(session('message'))
    <div class="plot_alert plot_alert--success">
        <i class="ti ti-circle-check"></i>
        <span>{{ session('message') }}</span>
    </div>
@endif
 
@if(session('error') || $errors->any())
    <div class="plot_alert plot_alert--warning">
        <i class="ti ti-alert-circle"></i>
        <span>{{ session('error') ?? $errors->first() }}</span>
    </div>
@endif
 
 
<div class="plot_market">
 
    {{-- LEFT SIDEBAR --}}
    <aside class="plot_sidebar">
 
        <div class="plot_sidebar_header">
            <p class="plot_section_label">Available Plots</p>
 
            <div class="plot_search_wrap">
                <i class="ti ti-search"></i>
                <input id="plot_search"
                       class="plot_search"
                       type="search"
                       placeholder="Search plots...">
            </div>
 
            <div class="plot_filter_row">
                <button class="plot_filter_btn active" data-filter="all">All</button>
                <button class="plot_filter_btn" data-filter="poor">Standard</button>
                <button class="plot_filter_btn" data-filter="normal">Raised Bed</button>
                <button class="plot_filter_btn" data-filter="rich">Premium</button>
            </div>
        </div>
 
        <ul class="plot_list" id="plot_list">
 
            @forelse($plots as $plot)
                <li class="plot_list_item"
                    data-plot='@json($plot)'
                    data-soil="{{ strtolower(str_replace(' ', '_', $plot->soil_quality)) }}"
                    tabindex="0">
 
                    <div class="plot_list_item_main">
                        <span class="plot_list_item_name">Plot #{{ $plot->id }}</span>
                        <span class="plot_list_item_zone">
                            <i class="ti ti-map-pin"></i>
                            {{ $plot->zone ?? 'Zone A' }}
                        </span>
                    </div>
 
                    <div class="plot_list_item_meta">
                        <span class="plot_badge plot_badge--{{ $plot->soil_quality === 'Premium Raised Beds' ? 'premium' : 'standard' }}">
                            {{ $plot->soil_quality }}
                        </span>
                        <span class="plot_list_item_size">{{ $plot->size }}m²</span>
                    </div>
 
                </li>
            @empty
                <li class="plot_list_empty">
                    <i class="ti ti-plant-off"></i>
                    <p>No plots available right now.</p>
                </li>
            @endforelse
 
        </ul>
 
    </aside>
 
 
    {{-- RIGHT PANEL --}}
    <div class="plot_panel">
 
        {{-- EMPTY STATE --}}
        <div class="plot_panel_empty" id="plot_empty">
            <div class="plot_panel_empty_icon">
                <i class="ti ti-map-2"></i>
            </div>
            <h2>Select a Plot</h2>
            <p>Choose a plot from the catalog to view details and apply.</p>
        </div>
 
 
        {{-- DETAIL PANEL --}}
        <div class="plot_panel_detail" id="plot_detail" hidden>
 
            {{-- Header --}}
            <div class="plot_detail_header">
                <div>
                    <p class="plot_section_label">Plot Details</p>
                    <h2 class="plot_detail_title" id="d_name"></h2>
                </div>
                <span class="plot_badge" id="d_soil_badge"></span>
            </div>
 
            {{-- Stats row --}}
            <div class="plot_detail_stats">
 
                <div class="plot_stat">
                    <span class="plot_stat_label">Size</span>
                    <span class="plot_stat_value"><span id="d_size"></span><span class="plot_stat_unit">m²</span></span>
                </div>
 
                <div class="plot_stat">
                    <span class="plot_stat_label">Sunlight</span>
                    <span class="plot_stat_value plot_stat_value--sm" id="d_sunlight"></span>
                </div>
 
                <div class="plot_stat">
                    <span class="plot_stat_label">Soil Quality</span>
                    <span class="plot_stat_value plot_stat_value--sm" id="d_soil"></span>
                </div>
 
                <div class="plot_stat">
                    <span class="plot_stat_label">Monthly Fee</span>
                    <span class="plot_stat_value">£<span id="d_fee"></span></span>
                </div>
 
            </div>
 
            {{-- Sunlight bar --}}
            <div class="plot_detail_sunlight">
                <span class="plot_section_label" style="margin-bottom:6px;">Sunlight Exposure</span>
                <div class="plot_sun_track">
                    <div class="plot_sun_fill" id="d_sun_fill"></div>
                </div>
                <div class="plot_sun_labels">
                    <span>Low</span><span>Medium</span><span>Full Sun</span>
                </div>
            </div>
 
            {{-- Zone / location meta --}}
            <div class="plot_detail_meta">
                <span class="plot_meta_item">
                    <i class="ti ti-map-pin"></i>
                    <span id="d_zone"></span>
                </span>
                <span class="plot_meta_item">
                    <i class="ti ti-ruler-2"></i>
                    <span id="d_dimensions"></span>
                </span>
            </div>
 
            {{-- Notes / description --}}
            <div class="plot_detail_notes" id="d_notes_wrap">
                <p class="plot_section_label">Notes</p>
                <p class="plot_notes_text" id="d_notes"></p>
            </div>
 
            {{-- CTA --}}
            <div class="plot_detail_cta">
                <button class="plot_apply_btn" id="open_apply_modal">
                    <i class="ti ti-clipboard-plus"></i>
                    Apply for this Plot
                </button>
                <p class="plot_cta_hint">Applications are reviewed by the garden warden.</p>
            </div>
 
        </div>
 
    </div>
 
</div>
 
 
{{-- ═══════════════════════════════════════
     APPLY MODAL
     ═══════════════════════════════════════ --}}
<div id="applyModal" class="plot_modal_backdrop" style="display:none;" aria-modal="true" role="dialog">
 
    <div class="plot_modal">
 
        <div class="plot_modal_header">
            <div>
                <p class="plot_section_label">Rental Application</p>
                <h3 class="plot_modal_title">Apply for <span id="modal_plot_name"></span></h3>
            </div>
            <button class="plot_modal_close" onclick="closeApplyModal()" aria-label="Close">
                <i class="ti ti-x"></i>
            </button>
        </div>
 
        <form method="POST" action="{{ route('rental.store') }}" class="plot_modal_form">
            @csrf
 
            <input type="hidden" name="plot_id" id="modal_plot_id">
 
            {{-- Message --}}
            <div class="plot_field">
                <label class="plot_label" for="modal_message">
                    Why do you want this plot?
                    <span>*</span>
                </label>
                <textarea name="message"
                          id="modal_message"
                          class="plot_input plot_textarea"
                          rows="3"
                          placeholder="Tell the warden a little about your plans..."></textarea>
            </div>
 
            {{-- Experience --}}
            <div class="plot_field">
                <label class="plot_label" for="modal_experience">Gardening Experience</label>
                <select name="experience_level" id="modal_experience" class="plot_input plot_select">
                    <option value="beginner">Beginner</option>
                    <option value="intermediate">Intermediate</option>
                    <option value="experienced">Experienced</option>
                </select>
            </div>
 
            {{-- Sharing --}}
            <div class="plot_field">
                <label class="plot_label">Would you like to share this plot?</label>
                <div class="plot_radio_group">
                    <label class="plot_radio_option">
                        <input type="radio" name="share" value="1" checked>
                        <span class="plot_radio_card">
                            <i class="ti ti-user"></i>
                            Solo
                        </span>
                    </label>
                    <label class="plot_radio_option">
                        <input type="radio" name="share" value="0.5">
                        <span class="plot_radio_card">
                            <i class="ti ti-users"></i>
                            Shared
                        </span>
                    </label>
                </div>
            </div>
 
            <div class="plot_modal_footer">
                <button type="button" class="plot_cancel_btn" onclick="closeApplyModal()">Cancel</button>
                <button type="submit" class="plot_submit_btn">
                    <i class="ti ti-send"></i>
                    Submit Application
                </button>
            </div>
 
        </form>
 
    </div>
 
</div>
 
@endsection
 
 
@push('scripts')
<script>
let activePlot = null;
 
const sunlightMap = { 'full': 100, 'high': 80, 'medium': 55, 'partial': 40, 'low': 20 };
 
/* ── List item click ─────────────────────────────────── */
document.querySelectorAll('.plot_list_item').forEach(el => {
 
    el.addEventListener('click', () => {
 
        document.querySelectorAll('.plot_list_item').forEach(i => i.classList.remove('active'));
        el.classList.add('active');
 
        activePlot = JSON.parse(el.dataset.plot);
 
        document.getElementById('plot_empty').style.display  = 'none';
        document.getElementById('plot_detail').hidden        = false;
 
        document.getElementById('d_name').textContent        = `Plot #${activePlot.id}`;
        document.getElementById('d_size').textContent        = activePlot.size ?? '—';
        document.getElementById('d_sunlight').textContent    = activePlot.sunlight_exposure ?? '—';
        document.getElementById('d_soil').textContent        = activePlot.soil_quality ?? '—';
        document.getElementById('d_fee').textContent         = activePlot.monthly_fee ?? '—';
        document.getElementById('d_zone').textContent        = activePlot.zone ?? 'Zone A';
        document.getElementById('d_dimensions').textContent  = activePlot.dimensions ?? `${activePlot.size}m²`;
 
        // Soil badge
        const badge = document.getElementById('d_soil_badge');
        badge.textContent  = activePlot.soil_quality ?? 'Standard';
        badge.className    = 'plot_badge plot_badge--' +
            (activePlot.soil_quality === 'Premium Raised Beds' ? 'premium' : 'standard');
 
        // Sunlight bar
        const sunKey  = (activePlot.sunlight_exposure ?? 'medium').toLowerCase();
        const sunPct  = sunlightMap[sunKey] ?? 55;
        document.getElementById('d_sun_fill').style.width = sunPct + '%';
 
        // Notes
        const notesWrap = document.getElementById('d_notes_wrap');
        if (activePlot.notes) {
            document.getElementById('d_notes').textContent = activePlot.notes;
            notesWrap.style.display = 'block';
        } else {
            notesWrap.style.display = 'none';
        }
    });
 
    // Keyboard support
    el.addEventListener('keydown', e => { if (e.key === 'Enter' || e.key === ' ') el.click(); });
});
 
 
/* ── Search ──────────────────────────────────────────── */
document.getElementById('plot_search').addEventListener('input', e => {
    const q = e.target.value.toLowerCase();
    document.querySelectorAll('.plot_list_item').forEach(el => {
        el.style.display = el.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});
 
 
/* ── Filter buttons ──────────────────────────────────── */
document.querySelectorAll('.plot_filter_btn').forEach(btn => {
    btn.addEventListener('click', () => {
 
        document.querySelectorAll('.plot_filter_btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
 
        const filter = btn.dataset.filter;
 
        document.querySelectorAll('.plot_list_item').forEach(el => {
            if (filter === 'all') {
                el.style.display = '';
                return;
            }
            const soil = (el.dataset.soil ?? '').toLowerCase();
            el.style.display = soil.includes(filter) ? '' : 'none';
        });
    });
});
 
 
/* ── Modal ───────────────────────────────────────────── */
function openApplyModal() {
    if (!activePlot) return;
    document.getElementById('modal_plot_id').value    = activePlot.id;
    document.getElementById('modal_plot_name').textContent = `Plot #${activePlot.id}`;
    document.getElementById('applyModal').style.display   = 'flex';
    document.body.style.overflow = 'hidden';
}
 
function closeApplyModal() {
    document.getElementById('applyModal').style.display = 'none';
    document.body.style.overflow = '';
}
 
document.getElementById('open_apply_modal').addEventListener('click', openApplyModal);
 
// Close on backdrop click
document.getElementById('applyModal').addEventListener('click', e => {
    if (e.target === e.currentTarget) closeApplyModal();
});
 
// Close on Escape
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeApplyModal();
});
</script>
@endpush
