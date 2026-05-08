@extends('layouts.app')

@section('title', 'Admin — Tool Management')

@push('styles')
@vite([
'resources/css/domain/tools.css'
])
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

    {{-- LEFT SIDEBAR --}}
    <aside class="tool_sidebar">

        <div class="tool_sidebar_header">

            <p class="tool_section_label">
                Tool inventory
            </p>

            <h2 class="tool_sidebar_title">
                Garden Tools
            </h2>

        </div>


        {{-- ADD TOOL --}}
        <div class="tool_card">

            <div class="tool_card_section">

                <p class="tool_section_label">
                    Add tool
                </p>

                <form method="POST"
                      action="{{ route('tools.store') }}"
                      class="tool_form">

                    @csrf

                    <div class="tool_field">

                        <label class="tool_label">
                            Tool Name
                        </label>

                        <input type="text"
                               name="name"
                               class="tool_input"
                               placeholder="Shovel, Rake..."
                               required>

                    </div>

                    <div class="tool_field">

                        <label class="tool_label">
                            Maintenance Threshold
                        </label>

                        <input type="number"
                               name="maintenance_threshold_hours"
                               class="tool_input"
                               placeholder="Hours">

                    </div>

                    <div class="tool_field">

                        <label class="tool_label">
                            Usage Status
                        </label>

                        <select name="usage_status"
                                class="tool_input">

                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>

                        </select>

                    </div>

                    <button type="submit"
                            class="tool_submit">

                        <i class="ti ti-plus"></i>
                        Create Tool

                    </button>

                </form>

            </div>

        </div>
<div class="tool_list">

    @foreach($tools as $tool)

        <button class="tool_list_item"
                onclick='showTool(@json($tool))'>

            <div class="tool_list_item_content">

                <span class="tool_list_item_name">
                    {{ $tool->name }}
                </span>

                <div class="tool_list_item_meta">

                    <span class="tool_badge tool_badge--{{ $tool->status }}">
                        {{ str_replace('_', ' ', $tool->status) }}
                    </span>

                    @if($tool->total_usage_hours >= $tool->maintenance_threshold_hours)

                        <span class="tool_flag tool_flag--maintenance">
                            Needs Service
                        </span>

                    @endif

                </div>

            </div>

        </button>

    @endforeach

</div>    </aside>


    {{-- RIGHT PANEL --}}
    <div class="tool_panel">

        {{-- DETAILS --}}
        <div class="tool_card tool_card--grow">

            <div id="tool-details"
                 class="tool_panel_empty">

                <h3>Select a Tool</h3>

                <p>
                    View usage analytics, maintenance state,
                    and booking statistics.
                </p>

            </div>

        </div>


        {{-- SYSTEM OVERVIEW --}}
        <div class="tool_card">

            <div class="tool_card_section">

                <div class="tool_overview_header">

                    <div>

                        <p class="tool_section_label">
                            System Overview
                        </p>

                        <h3 class="tool_overview_title">
                            Inventory Metrics
                        </h3>

                    </div>

                </div>

                <div class="tool_stats_grid">

                    <div class="tool_stat">

                        <span class="tool_stat_label">
                            Total Tools
                        </span>

                        <span class="tool_stat_value">
                            {{ $tools->count() }}
                        </span>

                    </div>

                    <div class="tool_stat">

                        <span class="tool_stat_label">
                            Available
                        </span>

                        <span class="tool_stat_value">
                            {{ $tools->where('status', 'available')->count() }}
                        </span>

                    </div>

                    <div class="tool_stat">

                        <span class="tool_stat_label">
                            In Use
                        </span>

                        <span class="tool_stat_value">
                            {{ $tools->where('status', 'in_use')->count() }}
                        </span>

                    </div>

                    <div class="tool_stat">

                        <span class="tool_stat_label">
                            Maintenance
                        </span>

                        <span class="tool_stat_value">
                            {{ $tools->where('status', 'maintenance')->count() }}
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection


@push('scripts')
<script>

function showTool(tool) {

    const needsMaintenance =
        tool.total_usage_hours >= tool.maintenance_threshold_hours;

    document.getElementById('tool-details').innerHTML = `

        <div class="tool_detail">

            <div class="tool_detail_header">

                <div>

                    <p class="tool_section_label">
                        Tool Details
                    </p>

                    <h2 class="tool_detail_title">
                        ${tool.name}
                    </h2>

                </div>

                <div class="tool_detail_header_actions">

                    <span class="tool_badge tool_badge--${tool.status}">
                        ${tool.status.replace('_', ' ')}
                    </span>

                    ${
                        needsMaintenance
                        ? `
                            <span class="tool_flag tool_flag--maintenance">
                                Needs Service
                            </span>
                        `
                        : ''
                    }

                </div>

            </div>


            <div class="tool_stats_grid">

                <div class="tool_stat">

                    <span class="tool_stat_label">
                        Usage Status
                    </span>

                    <span class="tool_stat_value">
                        ${tool.usage_status}
                    </span>

                </div>

                <div class="tool_stat">

                    <span class="tool_stat_label">
                        Total Bookings
                    </span>

                    <span class="tool_stat_value">
                        ${tool.bookings_count ?? 0}
                    </span>

                </div>

                <div class="tool_stat">

                    <span class="tool_stat_label">
                        Usage Hours
                    </span>

                    <span class="tool_stat_value">
                        ${tool.total_usage_hours}
                    </span>

                </div>

                <div class="tool_stat">

                    <span class="tool_stat_label">
                        Maintenance Threshold
                    </span>

                    <span class="tool_stat_value">
                        ${tool.maintenance_threshold_hours}
                    </span>

                </div>

            </div>


            <div class="tool_usage_section">

                <div class="tool_usage_header">

                    <span class="tool_section_label">
                        Maintenance Usage
                    </span>

                    <span class="tool_usage_text">
                        ${tool.total_usage_hours}
                        /
                        ${tool.maintenance_threshold_hours} hrs
                    </span>

                </div>

                <div class="tool_usage_bar">

                    <div class="tool_usage_fill"
                         style="
                            width:
                            ${
                                Math.min(
                                    (tool.total_usage_hours /
                                     tool.maintenance_threshold_hours) * 100,
                                    100
                                )
                            }%;
                         ">
                    </div>

                </div>

            </div>


            ${
                needsMaintenance || tool.status == "maintenance"
                ? `

                <div class="tool_action_section">

                    <form method="POST"
                          action="{{ route('tools.maintain') }}">

                        @csrf

                        <input type="hidden"
                               name="tool_id"
                               value="${Number(tool.id)}">

                        <button class="tool_submit">

                            <i class="ti ti-settings-check"></i>
                            Mark Maintained

                        </button>

                    </form>

                </div>

                `
                : ''
            }

${
   (Number(tool.waitlist_count ?? 0) > 0)
    ? `
        <div class="tool_action_section">

            <form method="POST"
                  action="{{ route('tools.waitlist.process') }}">

                @csrf

                <input type="hidden"
                       name="tool_id"
                       value="${tool.id}">

                <button class="tool_submit">

                    <i class="ti ti-player-play"></i>
                    Process Waitlist (${tool.waitlist_count})

                </button>

            </form>

        </div>
    `
    : ''
}

        </div>

    `;
}

</script>
@endpush