@extends('layouts.app')

@section('title', 'Tool Scan')

@push('styles')
    @vite(['resources/css/domain/tools.css'])

    <style>
        .tool_stats_grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 14px;
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

@if($errors->any())
    <div class="tool_alert tool_alert--warning">
        <i class="ti ti-alert-circle"></i>
        <span>{{ $errors->first() }}</span>
    </div>
@endif


<div class="tool_layout">

    <div class="tool_panel">

        <div class="tool_card tool_card--grow">

            <div class="tool_detail">

                {{-- HEADER --}}
                <div class="tool_overview_header">

                    <p class="tool_section_label">
                        Booking Scan
                    </p>

                    <h2 class="tool_detail_title">
                        {{ $booking->tool->name }}
                    </h2>

                </div>

                {{-- STATS --}}
                <div class="tool_stats_grid">

                    <div class="tool_stat">
                        <span class="tool_stat_label">Borrower</span>
                        <span class="tool_stat_value">{{ $booking->member->name }}</span>
                    </div>

                    <div class="tool_stat">
                        <span class="tool_stat_label">Status</span>

                        <span class="tool_stat_value">
                            <span class="tool_badge tool_badge--{{ $booking->status }}">
                                {{ $booking->status }}
                            </span>
                        </span>

                    </div>

                    <div class="tool_stat">
                        <span class="tool_stat_label">Start Time</span>
                        <span class="tool_stat_value">{{ $booking->start_time }}</span>
                    </div>

                    <div class="tool_stat">
                        <span class="tool_stat_label">End Time</span>
                        <span class="tool_stat_value">{{ $booking->end_time }}</span>
                    </div>

                </div>


                {{-- PICKUP (QR SCAN ACTION) --}}
                @if(is_null($booking->picked_up_at))

                    <form method="POST"
                          action="{{ route('tools.scan.process') }}"
                          class="tool_form"
                          style="margin-top:18px;">

                        @csrf

                        <input type="hidden" name="token" value="{{ $booking->qr_token }}">

                        <button class="tool_submit">
                            <i class="ti ti-package"></i>
                            Confirm Pickup (Scan)
                        </button>

                    </form>

                @endif


                {{-- CLEANING (QR SCAN ACTION) --}}
                @if(!is_null($booking->picked_up_at) && is_null($booking->cleaned_at))

                    <form method="POST"
                          action="{{ route('tools.scan.process') }}"
                          class="tool_form"
                          style="margin-top:18px;">

                        @csrf

                        <input type="hidden" name="token" value="{{ $booking->qr_token }}">

                        <button class="tool_submit">
                            <i class="ti ti-sparkles"></i>
                            Confirm Cleaning (Scan)
                        </button>

                    </form>

                @endif


                {{-- ========================= --}}
                {{-- COMPLETED --}}
                {{-- ========================= --}}
                @if($booking->cleaned_at)

                    <div class="tool_alert tool_alert--success" style="margin-top:18px;">
                        <i class="ti ti-circle-check"></i>
                        <span>Tool lifecycle completed successfully.</span>
                    </div>

                @endif

            </div>

        </div>

    </div>

</div>

@endsection