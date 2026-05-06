@extends('layouts.app')
@section('page-title', 'Deposit Seeds')


@push('styles')
    @vite(['resources/css/domain/seedbank.css'])
@endpush

@section('content')
<div class="seedbank_page">

  {{-- Alerts --}}
  @if(session('message'))
  <div class="seedbank_alert seedbank_alert--success" role="alert">
    <i class="ti ti-circle-check" aria-hidden="true" style="font-size:16px;margin-top:1px;flex-shrink:0"></i>
    <span>{{ session('message') }}</span>
  </div>
  @endif

  @if(session('error'))
  <div class="seedbank_alert seedbank_alert--warning" role="alert">
    <i class="ti ti-alert-circle" aria-hidden="true" style="font-size:16px;margin-top:1px;flex-shrink:0"></i>
    <span>{{ session('error') }}</span>
  </div>
  @endif

  @if($errors->any())
  <div class="seedbank_alert seedbank_alert--warning" role="alert">
    <i class="ti ti-alert-circle" aria-hidden="true" style="font-size:16px;margin-top:1px;flex-shrink:0"></i>
    <span>{{ $errors->first() }}</span>
  </div>
  @endif

  {{-- Page heading --}}
  <div class="seedbank_header">
    <div class="seedbank_header_icon"><i class="ti ti-plant-2" aria-hidden="true"></i></div>
    <div>
      <h1 class="seedbank_title">Deposit seeds</h1>
      <p class="seedbank_subtitle">Add seeds to your inventory or contribute to the community market</p>
    </div>
  </div>

  {{-- Form card --}}
  <form method="POST" action="{{ route('seedbank.deposit.store') }}" novalidate>
    @csrf

    <div class="seedbank_card">

      {{-- Section: Seed details --}}
      <div class="seedbank_card_section">
        <p class="seedbank_section_label">Seed details</p>

        <div class="seedbank_field_grid" style="margin-bottom:14px">
          <div class="seedbank_field">
            <label class="seedbank_label" for="seed_type">Seed type <span aria-hidden="true">*</span></label>
            <input class="seedbank_input" id="seed_type" name="seed_type"
              placeholder="e.g. Roma Tomato"
              value="{{ old('seed_type') }}" required>
          </div>
          <div class="seedbank_field">
            <label class="seedbank_label" for="origin">Origin</label>
            <input class="seedbank_input" id="origin" name="origin"
              placeholder="e.g. Oaxaca, Mexico"
              value="{{ old('origin') }}">
          </div>
        </div>

        <div class="seedbank_field_grid">
          <div class="seedbank_field">
            <label class="seedbank_label" for="quantity">Quantity <span aria-hidden="true">*</span></label>
            <input class="seedbank_input" id="quantity" name="quantity"
              type="number" placeholder="0" min="1"
              value="{{ old('quantity') }}" required>
            <span class="seedbank_input_hint">Number of seeds in this batch</span>
          </div>
          <div class="seedbank_field">
            <label class="seedbank_label" for="age">Age (months)</label>
            <input class="seedbank_input" id="age" name="age"
              type="number" placeholder="0" min="0"
              value="{{ old('age') }}">
            <span class="seedbank_input_hint">How old is this batch?</span>
          </div>
        </div>
      </div>

      <hr class="seedbank_card_divider">

      {{-- Section: Viability --}}
      <div class="seedbank_card_section">
        <p class="seedbank_section_label">Viability</p>
        <div class="seedbank_field">
          <label class="seedbank_label" for="viability">Germination rate <span aria-hidden="true">*</span></label>
          <div style="display:flex;align-items:center;gap:10px">
            <input class="seedbank_input" id="viability" name="viability"
              type="number" placeholder="85" min="0" max="100"
              value="{{ old('viability') }}" required style="max-width:120px">
            <span style="font-size:14px;color:var(--color-text-secondary)">%</span>
            <span id="seedbank_viability_badge" class="seedbank_viability_badge" style="display:none"></span>
          </div>
          <span class="seedbank_input_hint">Estimated percentage of seeds that will germinate</span>
        </div>
      </div>

      <hr class="seedbank_card_divider">

      {{-- Section: Destination --}}
      <div class="seedbank_card_section">
        <p class="seedbank_section_label">Destination</p>
        <div class="seedbank_destination_group">

          <label class="seedbank_destination_option">
            <input type="radio" name="owner_type" value="inventory"
              {{ old('owner_type', 'inventory') === 'inventory' ? 'checked' : '' }}>
            <div class="seedbank_destination_card">
              <div class="seedbank_destination_card_icon"><i class="ti ti-archive" aria-hidden="true"></i></div>
              <p class="seedbank_destination_card_name">My inventory</p>
              <p class="seedbank_destination_card_desc">Seeds stored privately for your own use</p>
            </div>
          </label>

          <label class="seedbank_destination_option">
            <input type="radio" name="owner_type" value="market"
              {{ old('owner_type') === 'market' ? 'checked' : '' }}>
            <div class="seedbank_destination_card">
              <div class="seedbank_destination_card_icon"><i class="ti ti-basket" aria-hidden="true"></i></div>
              <p class="seedbank_destination_card_name">Community market</p>
              <p class="seedbank_destination_card_desc">Share with other gardeners to earn credits</p>
            </div>
          </label>

        </div>
      </div>

      {{-- Footer --}}
      <div class="seedbank_footer">
        <a href="{{ route('seedbank.profile') }}" class="seedbank_cancel">Cancel</a>
        <button type="submit" class="seedbank_submit">
          <i class="ti ti-leaf" aria-hidden="true"></i>
          Deposit seeds
        </button>
      </div>

    </div>
  </form>

</div>

<script>
  const seedbankViabilityInput = document.getElementById('viability');
  const seedbankViabilityBadge = document.getElementById('seedbank_viability_badge');

  function seedbank_updateViabilityBadge() {
    const v = parseInt(seedbankViabilityInput.value);
    if (isNaN(v) || seedbankViabilityInput.value === '') {
      seedbankViabilityBadge.style.display = 'none'; return;
    }
    seedbankViabilityBadge.style.display = 'inline';
    if (v >= 80)      { seedbankViabilityBadge.textContent = 'Excellent'; seedbankViabilityBadge.style.cssText += 'background:#e8f0dc;color:#3b6d11'; }
    else if (v >= 60) { seedbankViabilityBadge.textContent = 'Good';      seedbankViabilityBadge.style.cssText += 'background:#faeeda;color:#854f0b'; }
    else if (v >= 40) { seedbankViabilityBadge.textContent = 'Fair';      seedbankViabilityBadge.style.cssText += 'background:#faeeda;color:#854f0b'; }
    else              { seedbankViabilityBadge.textContent = 'Low';       seedbankViabilityBadge.style.cssText += 'background:#fcebeb;color:#a32d2d'; }
  }

  seedbankViabilityInput.addEventListener('input', seedbank_updateViabilityBadge);
  seedbank_updateViabilityBadge(); {{-- run on load to restore old() value --}}
</script>
@endsection