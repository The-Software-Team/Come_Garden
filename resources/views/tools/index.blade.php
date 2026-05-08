@extends('layouts.app')

@section('title', 'Tool Library')

@push('styles')
@vite([
    'resources/css/domain/tools.css'
])
<style>
.tool_modal {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.4);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 999;
}

.tool_modal_box {
    width: 420px;
    background: var(--color-background-primary);
    border-radius: var(--border-radius-lg);
    padding: 20px;
    border: 0.5px solid var(--color-border-tertiary);
}
</style>
@endpush

@section('content')

{{-- Alerts --}}
@if(session('message'))
    <div class="tool_alert tool_alert--success">
        <i class="ti ti-circle-check"></i>
        <span>{{ session('message') }}</span>
    </div>
@endif

@if(session('error'))
    <div class="tool_alert tool_alert--warning">
        <i class="ti ti-alert-circle"></i>
        <span>{{ session('error') }}</span>
    </div>
@endif


<div class="tool_layout">

    {{-- LEFT SIDEBAR --}}
    <aside class="tool_sidebar">

        <div class="tool_sidebar_header">
            <p class="tool_section_label">Community Inventory</p>
            <h2 class="tool_sidebar_title">Tool Library</h2>
        </div>

        <div class="tool_list">

            @foreach($tools as $tool)
                <button class="tool_list_item"
                        onclick='showTool(@json($tool))'>

                    <span class="tool_list_item_name">
                        {{ $tool->name }}
                    </span>

                    <span class="tool_badge tool_badge--{{ $tool->status }}">
                        {{ str_replace('_', ' ', $tool->status) }}
                    </span>

                </button>
            @endforeach

        </div>

    </aside>


    {{-- RIGHT PANEL --}}
    <div class="tool_panel">

        {{-- TOOL DETAILS --}}
        <div class="tool_card tool_card--grow">

            <div id="tool-details" class="tool_panel_empty">
                <h3>Select a Tool</h3>
                <p>View tool details, availability, and create bookings.</p>
            </div>

        </div>


        {{-- BOOKINGS --}}
        <div class="tool_card">

            <div class="tool_card_section">

                <div class="tool_overview_header">
                    <div>
                        <p class="tool_section_label">My Bookings</p>
                        <h3 class="tool_overview_title">Active Reservations</h3>
                    </div>
                </div>


                @if($bookings->isEmpty())

                    <div class="tool_empty_bookings">
                        <i class="ti ti-calendar-off"></i>
                        <p>No active bookings yet.</p>
                    </div>

                @else

                    <div class="tool_booking_list">

                        @foreach($bookings as $booking)

                            <div class="tool_booking_card">

                                <div class="tool_booking_top">

                                    <div>
                                        <h4 class="tool_booking_name">
                                            {{ $booking->tool->name }}
                                        </h4>

                                        <p class="tool_booking_due">
                                            Due {{ $booking->end_time }}
                                        </p>
                                    </div>

                                    <span class="tool_badge tool_badge--{{ $booking->status }}">
                                        {{ $booking->status }}
                                    </span>

                                </div>

                                {{-- QR CODE --}}
                                @if($booking->qr_token)
                                    <div class="tool_booking_qr">
                                        <img
                                            src="https://api.qrserver.com/v1/create-qr-code/?size=140x140&data={{ urlencode(route('tools.scan', ['token' => $booking->qr_token])) }}"
                                            class="tool_qr_image">
                                    </div>
                                @endif


                                <div class="tool_booking_actions">

                                    {{-- RETURN --}}
                                    <form method="POST" action="{{ route('tools.return') }}">
                                        @csrf
                                        <input type="hidden" name="booking_id" value="{{ $booking->id }}">

                                        <button class="tool_btn tool_btn--success">
                                            <i class="ti ti-check"></i>
                                            Return
                                        </button>
                                    </form>

                                    {{-- DAMAGE BUTTON (FIXED) --}}
                                    <button type="button"
                                            class="tool_btn tool_btn--danger"
                                            onclick="openDamageModal({{ $booking->id }})">

                                        <i class="ti ti-alert-triangle"></i>
                                        Report

                                    </button>

                                </div>

                            </div>

                        @endforeach

                    </div>

                @endif

            </div>

        </div>

    </div>

</div>


{{-- DAMAGE MODAL (OUTSIDE LOOP SAFE) --}}
<div id="damageModal" class="tool_modal" style="display:none;">

    <div class="tool_modal_box">

        <h3 class="tool_detail_title">Report Damage</h3>

        <form method="POST" action="{{ route('tools.damage') }}" class="tool_form">

            @csrf

            <input type="hidden" name="booking_id" id="damage_booking_id">

            <label class="tool_label">Severity</label>
            <select name="severity" class="tool_input">
                <option value="low">Low</option>
                <option value="medium">Medium</option>
                <option value="high">High</option>
            </select>

            <label class="tool_label">Reason</label>
            <textarea name="reason"
                      class="tool_input"
                      rows="4"
                      placeholder="Describe the issue..."></textarea>

            <div style="display:flex; gap:10px; margin-top:12px;">

                <button type="submit" class="tool_submit">
                    Submit Report
                </button>

                <button type="button"
                        class="tool_btn tool_btn--danger"
                        onclick="closeDamageModal()">
                    Cancel
                </button>

            </div>

        </form>

    </div>

</div>

@endsection


@push('scripts')
<script>

function showTool(tool) {

    document.getElementById('tool-details').innerHTML = `
        <div class="tool_detail">

            <div class="tool_detail_header">

                <div>
                    <p class="tool_section_label">Tool Details</p>
                    <h2 class="tool_detail_title">${tool.name}</h2>
                </div>

                <span class="tool_badge tool_badge--${tool.status}">
                    ${tool.status.replace('_',' ')}
                </span>

            </div>

            <div class="tool_stats_grid">

                <div class="tool_stat">
                    <span class="tool_stat_label">Usage Status</span>
                    <span class="tool_stat_value">${tool.usage_status}</span>
                </div>

                <div class="tool_stat">
                    <span class="tool_stat_label">Total Hours</span>
                    <span class="tool_stat_value">${tool.total_usage_hours}</span>
                </div>

                <div class="tool_stat">
                    <span class="tool_stat_label">Maintenance Threshold</span>
                    <span class="tool_stat_value">${tool.maintenance_threshold_hours}</span>
                </div>

            </div>

            <div class="tool_booking_section">

                <p class="tool_section_label">Book Tool</p>

                <form method="POST" action="{{ route('tools.book') }}" class="tool_booking_form">
                    @csrf

                    <input type="hidden" name="tool_name" value="${tool.name}">

                    <input type="number"
                           name="duration_hours"
                           min="1"
                           required
                           placeholder="Hours"
                           class="tool_input">

                    <button type="submit" class="tool_submit">
                        <i class="ti ti-calendar-plus"></i>
                        Book Tool
                    </button>

                </form>

            </div>

        </div>
    `;
}


/* ===== DAMAGE MODAL FIX ===== */

function openDamageModal(bookingId) {
    document.getElementById('damage_booking_id').value = bookingId;
    document.getElementById('damageModal').style.display = 'flex';
}

function closeDamageModal() {
    document.getElementById('damageModal').style.display = 'none';
}

</script>
@endpush