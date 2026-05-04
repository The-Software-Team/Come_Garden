@extends('layouts.app')

@section('title', 'Marketplace')

@section('styles')
@vite([
'resources/css/domain/marketplace.css'
])
@endsection

@section('dynamics')
<script>
function showListing(listing) {

    document.getElementById('listing-details').innerHTML = `

        <div class="details-header">
            <h2>${listing.item}</h2>
            <span class="badge">${listing.status ?? 'active'}</span>
        </div>

        <div class="grid-info">

            <div class="info-box">
                <div class="info-label">Quantity</div>
                <div class="info-value">${listing.quantity}</div>
            </div>

            <div class="info-box">
                <div class="info-label">Type</div>
                <div class="info-value">${listing.type}</div>
            </div>

            <div class="info-box">
                <div class="info-label">Request</div>
                <div class="info-value">${listing.request ?? '-'}</div>
            </div>

        </div>

        <div class="action-section">

            <form method="POST" action="{{ route('marketplace.trades.store') }}">
                @csrf

                <input type="hidden" name="listing_id" value="${listing.id}">

                <button type="submit" class="btn-primary">
                    Request Trade
                </button>

            </form>

        </div>
    `;
}
</script>
@endsection

@section('content')

@if(session('message'))
    <div class="alert alert--success">
        {{ session('message') }}
    </div>
@endif

<div class="grid grid-cols-12 gap-6">

    {{-- LEFT: LISTINGS --}}
    <div class="col-span-4">
        <h2>📦 Listings</h2>

        <div class="tool-list">
            @foreach($listings as $listing)
                <div class="tool-item"
                     onclick='showListing(@json($listing))'>

                    {{ $listing->item }}

                    <span class="status">{{ $listing->status ?? 'active' }}</span>
                </div>
            @endforeach
        </div>

        {{-- CREATE LISTING --}}
        <div class="panel mt-4">

            <h3>➕ Create Listing</h3>

            <form method="POST" action="{{ route('marketplace.listings.store') }}">
                @csrf

                <input type="text" name="item" placeholder="Item" class="input">
                <input type="number" name="quantity" placeholder="Quantity" class="input">
                <input type="text" name="type" placeholder="Type (normal/flash)" class="input">
                <input type="text" name="request" placeholder="Request (optional)" class="input">

                <button class="btn-primary">Create</button>
            </form>

        </div>
    </div>

    {{-- RIGHT: DETAILS --}}
    <div class="col-span-8">

        <div class="panel">
            <div id="listing-details">
                <div class="empty-state">
                    <h3>Select a Listing</h3>
                    <p>View details and request trades.</p>
                </div>
            </div>
        </div>

        {{-- QUESTIONS --}}
        <div class="panel mt-6">

            <h3>❓ Questions</h3>

            {{-- ASK QUESTION --}}
            <form method="POST" action="{{ route('marketplace.questions.store') }}" class="mb-4">
                @csrf

                <input type="text" name="content" placeholder="Ask something..." class="input">
                <input type="number" name="bounty" placeholder="Bounty" class="input">

                <button class="btn-primary">Ask</button>
            </form>

            {{-- LIST QUESTIONS --}}
            @foreach($questions as $question)
                <div class="booking-card">

                    <div>
                        <strong>Q:</strong> {{ $question->content }}
                    </div>

                    <div>
                        <strong>Status:</strong> {{ $question->status ?? 'open' }}
                    </div>

                    <div>
                        <strong>Bounty:</strong> {{ $question->bounty ?? 0 }}
                    </div>

                    {{-- ANSWERS --}}
                    <div class="mt-2">
                        @foreach($question->answers ?? [] as $answer)
                            <div class="info-box">
                                <strong>A:</strong> {{ $answer->content }}
                            </div>
                        @endforeach
                    </div>

                    {{-- ANSWER FORM --}}
                    <form method="POST" action="{{ route('marketplace.answers.store') }}">
                        @csrf

                        <input type="hidden" name="question_id" value="{{ $question->id }}">
                        <input type="text" name="content" placeholder="Your answer..." class="input">

                        <button class="btn-success">Answer</button>
                    </form>

                </div>
            @endforeach

        </div>

    </div>

</div>

@endsection