@extends('layouts.app')
@section('title', 'Seed Bank Admin')
@push('styles')
    @vite(['resources/css/domain/seedbank.css'])
    <style>
        .admin_alerts_grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
            margin-bottom: 1.5rem;
        }
        .admin_alert_col {
            background: var(--color-background-primary);
            border: 0.5px solid var(--color-border-tertiary);
            border-radius: var(--border-radius-lg);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        .admin_alert_col_header {
            padding: 11px 16px;
            border-bottom: 0.5px solid var(--color-border-tertiary);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--color-background-secondary);
        }
        .admin_col_title {
            font-size: 11px;
            font-weight: 500;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--color-text-secondary);
        }
        @media (max-width: 640px) {
            .admin_alerts_grid { grid-template-columns: 1fr; }
        }
    </style>
@endpush
 
@section('content')

<div class="seedbank_page" style="max-width: 960px;">
 
    {{-- FLASH MESSAGE --}}
    @if(session('message'))
    <div class="seedbank_alert seedbank_alert--success">
        <i class="ti ti-circle-check"></i>
        <span>{{ session('message') }}</span>
    </div>
    @endif
 
    {{-- HEADER --}}
    <div class="seedbank_header" style="justify-content: space-between;">
        <div style="display: flex; align-items: center; gap: 14px;">
            <div class="seedbank_header_icon">
                <i class="ti ti-shield-check"></i>
            </div>
            <div>
                <h1 class="seedbank_title">Admin Dashboard</h1>
                <p class="seedbank_subtitle">Monitor seed health and inventory alerts</p>
            </div>
        </div>
        <button class="seedbank_submit" onclick="openInventoryModal()">
            <i class="ti ti-plus"></i>Add inventory
        </button>
    </div>


    {{-- DETAIL PANEL (shown on click) --}}
    <section class="seedbank_browse_panel" id="detail-panel" style="display: none;">
        <div class="seedbank_panel_detail" id="alert-details"></div>
    </section>
 
    {{-- TWO-COLUMN ALERTS --}}
    <div class="admin_alerts_grid">
 
        {{-- COLUMN 1: HEALTH ALERTS --}}
        <div class="admin_alert_col">
            <div class="admin_alert_col_header">
                <span class="admin_col_title">
                    <i class="ti ti-plant" style="font-size: 13px; vertical-align: -1px; margin-right: 5px; color: var(--seedbank_green_dark);"></i>
                    Health alerts
                </span>
                <span class="seedbank_count_pill">{{ count($healthAlerts) }}</span>
            </div>
 
            @if(count($healthAlerts))
            <ul class="seedbank_list">
                @foreach($healthAlerts as $alert)
                <li class="seedbank_list_item"
                    onclick='showAlert(@json(array_merge($alert, ["type" => "health"])))'
                    tabindex="0"
                    role="button"
                    aria-label="{{ $alert['seed_type'] }} — {{ $alert['status'] }}">
                    <div style="display: flex; align-items: center; gap: 9px; min-width: 0;">
                        <div class="seedbank_avatar" style="width: 30px; height: 30px; border-radius: 8px; flex-shrink: 0;">
                            <i class="ti ti-plant" style="font-size: 14px;"></i>
                        </div>
                        <span class="seedbank_list_item_name" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            {{ $alert['seed_type'] }}
                        </span>
                    </div>
                    @if($alert['status'] === 'expired')
                        <span class="seedbank_badge" style="background: #fcebeb; color: #791f1f; flex-shrink: 0;">Expired</span>
                    @else
                        <span class="seedbank_badge seedbank_badge--market" style="flex-shrink: 0;">Expiring</span>
                    @endif
                </li>
                @endforeach
            </ul>
            @else
            <p class="seedbank_list_empty">No health alerts.</p>
            @endif
        </div>
 
        {{-- COLUMN 2: INVENTORY ALERTS --}}
        <div class="admin_alert_col">
            <div class="admin_alert_col_header">
                <span class="admin_col_title">
                    <i class="ti ti-package" style="font-size: 13px; vertical-align: -1px; margin-right: 5px; color: var(--seedbank_amber_text);"></i>
                    Inventory alerts
                </span>
                <span class="seedbank_count_pill">{{ count($inventoryAlerts) }}</span>
            </div>
 
            @if(count($inventoryAlerts))
            <ul class="seedbank_list">
                @foreach($inventoryAlerts as $item)
                <li class="seedbank_list_item"
                    onclick='showAlert(@json(array_merge($item, ["type" => "inventory"])))'
                    tabindex="0"
                    role="button"
                    aria-label="{{ $item['name'] }} — reorder required">
                    <div style="display: flex; align-items: center; gap: 9px; min-width: 0;">
                        <div class="seedbank_avatar" style="width: 30px; height: 30px; border-radius: 8px; background: var(--seedbank_amber_bg); flex-shrink: 0;">
                            <i class="ti ti-package" style="font-size: 14px; color: var(--seedbank_amber_text);"></i>
                        </div>
                        <span class="seedbank_list_item_name" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            {{ $item['name'] }}
                        </span>
                    </div>
                    <span class="seedbank_badge seedbank_badge--market" style="flex-shrink: 0;">Reorder</span>
                </li>
                @endforeach
            </ul>
            @else
            <p class="seedbank_list_empty">No inventory alerts.</p>
            @endif
        </div>
 
    </div>
 
 
</div>
 
{{-- ADD INVENTORY MODAL --}}
<div id="inventory-modal"
     class="hidden"
     style="position: fixed; inset: 0; background: rgba(0,0,0,.38); display: flex; align-items: center; justify-content: center; z-index: 100;"
     role="dialog"
     aria-modal="true"
     aria-labelledby="modal-title">
    <div class="seedbank_card" style="width: 420px; max-width: 94vw;">
        <div class="seedbank_card_section" style="display: flex; align-items: center; justify-content: space-between; padding-bottom: 1rem; border-bottom: 0.5px solid var(--color-border-tertiary);">
            <h3 class="seedbank_title" id="modal-title" style="font-size: 16px;">Add inventory item</h3>
            <button onclick="closeInventoryModal()"
                    aria-label="Close modal"
                    style="background: none; border: none; cursor: pointer; color: var(--color-text-secondary); display: flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: var(--border-radius-md);">
                <i class="ti ti-x" style="font-size: 18px;"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('admin_seedbank.store') }}">
            @csrf
            <div class="seedbank_card_section" style="display: flex; flex-direction: column; gap: 14px;">
                <div class="seedbank_field">
                    <label class="seedbank_label" for="inv_name">Item name <span aria-hidden="true">*</span></label>
                    <input class="seedbank_input" type="text" id="inv_name" name="name" placeholder="e.g. Seed envelopes" required>
                </div>
                <div class="seedbank_field_grid">
                    <div class="seedbank_field">
                        <label class="seedbank_label" for="inv_qty">Current quantity <span aria-hidden="true">*</span></label>
                        <input class="seedbank_input" type="number" id="inv_qty" name="quantity" placeholder="e.g. 100" min="0" required>
                    </div>
                    <div class="seedbank_field">
                        <label class="seedbank_label" for="inv_thresh">Reorder threshold <span aria-hidden="true">*</span></label>
                        <input class="seedbank_input" type="number" id="inv_thresh" name="threshold" placeholder="e.g. 30" min="0" required>
                    </div>
                </div>
            </div>
            <div class="seedbank_footer">
                <button type="button" class="seedbank_cancel" onclick="closeInventoryModal()">Cancel</button>
                <button type="submit" class="seedbank_submit">
                    <i class="ti ti-check"></i>Save item
                </button>
            </div>
        </form>
    </div>
</div>
 
@endsection
 
@push('scripts')
<script>
function showAlert(alert) {
    document.querySelectorAll('.seedbank_list_item').forEach(el => el.classList.remove('active'));
 
    document.querySelectorAll('.seedbank_list_item').forEach(el => {
        const nameEl = el.querySelector('.seedbank_list_item_name');
        const match  = alert.type === 'health' ? alert.seed_type : alert.name;
        if (nameEl && nameEl.textContent.trim() === match) el.classList.add('active');
    });
 
    const panel = document.getElementById('detail-panel');
    const box   = document.getElementById('alert-details');
    panel.style.display = 'block';
 
    let html = '';
 
    if (alert.type === 'health') {
        const isExpired  = alert.status === 'expired';
        const badgeStyle = isExpired ? 'background:#fcebeb;color:#791f1f;' : 'background:#faeeda;color:#633806;';
        const badgeText  = isExpired ? 'Expired' : 'Expiring soon';
 
        html = `
            <div class="seedbank_detail_header">
                <h2 class="seedbank_detail_title">${alert.seed_type}</h2>
                <span class="seedbank_badge" style="${badgeStyle}">${badgeText}</span>
            </div>
            <div class="seedbank_detail_stats" style="grid-template-columns: repeat(3, 1fr);">
                <div class="seedbank_stat">
                    <span class="seedbank_stat_label">Status</span>
                    <span class="seedbank_stat_value" style="font-size:14px;font-weight:500;">${badgeText}</span>
                </div>
                <div class="seedbank_stat">
                    <span class="seedbank_stat_label">Batch ID</span>
                    <span class="seedbank_stat_value" style="font-size:14px;">${alert.batch_id ?? '—'}</span>
                </div>
                <div class="seedbank_stat">
                    <span class="seedbank_stat_label">Stored</span>
                    <span class="seedbank_stat_value" style="font-size:14px;">${alert.stored ?? '—'}</span>
                </div>
            </div>
            ${alert.quantity ? `
            <div style="padding:14px 20px;border-bottom:0.5px solid var(--color-border-tertiary);">
                <span class="seedbank_stat_label">Quantity on hand</span>
                <span style="font-size:22px;font-weight:500;color:var(--color-text-primary);display:block;margin-top:4px;">${alert.quantity}</span>
            </div>` : ''}
            <div class="seedbank_withdraw_section"></div>`;
    }
 
    if (alert.type === 'inventory') {
        html = `
            <div class="seedbank_detail_header">
                <h2 class="seedbank_detail_title">${alert.name}</h2>
                <span class="seedbank_badge seedbank_badge--market">Reorder required</span>
            </div>
            <div class="seedbank_detail_stats" style="grid-template-columns:1fr 1fr;">
                <div class="seedbank_stat">
                    <span class="seedbank_stat_label">Current stock</span>
                    <span class="seedbank_stat_value">${alert.quantity}</span>
                </div>
                <div class="seedbank_stat">
                    <span class="seedbank_stat_label">Reorder at</span>
                    <span class="seedbank_stat_value">${alert.threshold ?? '—'}</span>
                </div>
            </div>
            <div style="padding:14px 20px;border-bottom:0.5px solid var(--color-border-tertiary);">
                <div class="seedbank_alert seedbank_alert--warning" style="margin:0;">
                    <i class="ti ti-alert-triangle"></i>
                    <span>Stock is below the reorder threshold. Place a new order to avoid running out.</span>
                </div>
            </div>
            <div class="seedbank_withdraw_section"></div>`;
    }
 
    box.innerHTML = html;
    panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}
 
function openInventoryModal() {
    const modal = document.getElementById('inventory-modal');
    modal.classList.remove('hidden');
    modal.style.display = 'flex';
}
 
function closeInventoryModal() {
    const modal = document.getElementById('inventory-modal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
}
 
document.querySelectorAll('.seedbank_list_item[tabindex]').forEach(el => {
    el.addEventListener('keydown', e => {
        if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); el.click(); }
    });
});
</script>
@endpush
