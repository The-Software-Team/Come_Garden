@extends('layouts.volunteer')

@section('title', 'Proposals')

@section('content')
<div class="page-hd">
  <div>
    <div class="page-title">🗳 Proposals</div>
    <div class="page-sub">Participate in community decision-making</div>
  </div>
  <button class="btn btn-primary" onclick="document.getElementById('create-proposal').classList.toggle('hidden')">+ New Proposal</button>
</div>

<style>.hidden { display: none; }</style>

<div id="create-proposal" class="card hidden" style="margin-bottom:1.5rem">
  <h3 style="font-size:1rem; font-weight:700; margin-bottom:1rem; color:var(--green-800)">Create a Proposal</h3>
  <form method="POST" action="{{ route('volunteer.proposals.create') }}">
    @csrf
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; max-width:600px">
      <div style="grid-column:1/-1">
        <label style="font-size:0.8rem; font-weight:600; display:block; margin-bottom:4px">Title</label>
        <input class="input" type="text" name="title" required maxlength="255">
      </div>
      <div style="grid-column:1/-1">
        <label style="font-size:0.8rem; font-weight:600; display:block; margin-bottom:4px">Description</label>
        <textarea class="input" name="description" rows="3" required></textarea>
      </div>
      <div>
        <label style="font-size:0.8rem; font-weight:600; display:block; margin-bottom:4px">Estimated Cost ($)</label>
        <input class="input" type="number" step="0.01" min="0" name="estimated_cost" required>
      </div>
      <div>
        <label style="font-size:0.8rem; font-weight:600; display:block; margin-bottom:4px">Voting Ends At</label>
        <input class="input" type="datetime-local" name="voting_ends_at" required>
      </div>
    </div>
    <button class="btn btn-primary btn-sm" type="submit" style="margin-top:1rem">Submit Proposal</button>
  </form>
</div>

@forelse($proposals as $proposal)
  <div class="proposal">
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:6px">
      <span class="badge badge-{{ $proposal->status === 'open' ? 'green' : 'gray' }}">
        {{ $proposal->status === 'open' ? '🗳 Open' : ($proposal->status === 'approved' ? '✅ Approved' : '❌ Rejected') }}
        @if($proposal->status === 'open')
          · Closes {{ \Carbon\Carbon::parse($proposal->voting_ends_at)->diffForHumans() }}
        @endif
      </span>
      <span style="font-size:0.76rem; color:var(--warm-gray)">P-{{ str_pad($proposal->id, 4, '0', STR_PAD_LEFT) }}</span>
    </div>
    <div class="proposal-title">{{ $proposal->title }}</div>
    <div class="proposal-desc">{{ $proposal->description }}</div>

    @php
      $yes = $proposal->votes->where('vote', 'yes')->count();
      $no = $proposal->votes->where('vote', 'no')->count();
      $abs = $proposal->votes->where('vote', 'abstain')->count();
      $total = max($yes + $no + $abs, 1);
      $userVote = $proposal->votes->where('member_id', auth()->id())->first();
    @endphp

    <div class="vote-meta">
      <span class="yes">✅ For: {{ $yes }}</span>
      <span class="no">❌ Against: {{ $no }}</span>
      <span class="abs">⬜ Abstain: {{ $abs }}</span>
      <span class="abs">Total: {{ $total }} votes</span>
    </div>
    <div class="vote-bar">
      <div class="vote-yes" style="width:{{ round($yes / $total * 100) }}%"></div>
      <div class="vote-no" style="width:{{ round($no / $total * 100) }}%"></div>
      <div class="vote-neutral" style="width:{{ round($abs / $total * 100) }}%"></div>
    </div>

    @if($proposal->status === 'open')
      <div class="vote-actions">
        @if($userVote)
          <button class="btn btn-primary btn-sm" style="cursor:default; background:var(--green-400)">
            {{ $userVote->vote === 'yes' ? '✅ You voted For' : ($userVote->vote === 'no' ? '❌ You voted Against' : '⬜ You abstained') }}
          </button>
        @else
          <form method="POST" action="{{ route('volunteer.proposals.vote', $proposal->id) }}" style="display:flex; gap:7px">
            @csrf
            <button class="btn btn-primary btn-sm" name="vote" value="yes" type="submit">✅ Vote For</button>
            <button class="btn btn-outline btn-sm" name="vote" value="no" type="submit">❌ Vote Against</button>
            <button class="btn btn-outline btn-sm" name="vote" value="abstain" type="submit">⬜ Abstain</button>
          </form>
        @endif
        @if(auth()->user()->isAdmin())
          <form method="POST" action="{{ route('volunteer.proposals.close', $proposal->id) }}" style="display:inline">
            @csrf
            <button class="btn btn-outline btn-sm" type="submit" style="border-color:var(--earth-400)">Close Voting</button>
          </form>
        @endif
      </div>
    @endif
  </div>
@empty
  <div class="card" style="text-align:center; padding:2rem">
    <div style="font-size:2rem; margin-bottom:0.5rem">🗳</div>
    <p style="color:var(--warm-gray)">No proposals yet. Be the first to create one!</p>
  </div>
@endforelse
@endsection
