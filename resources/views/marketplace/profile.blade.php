@extends('layouts.app')

@section('title', 'My Marketplace')

@section('styles')
@vite([
'resources/css/domain/marketplace.css'
])
@endsection

@section('dynamics')
<script>
function showMyListing(listing) {
    document.getElementById('member-details').innerHTML = `

        <div class="details-header">
            <h2>${listing.item}</h2>
            <span class="badge">${listing.status}</span>
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

    {{-- LEFT: MY LISTINGS --}}
    <div class="col-span-4">
        <h2>📦 My Listings</h2>

        <div class="tool-list">
            @foreach($listings as $listing)
                <div class="tool-item"
                     onclick='showMyListing(@json($listing))'>

                    {{ $listing->item }}
                    <span class="status">{{ $listing->status }}</span>

                </div>
            @endforeach
        </div>
    </div>

    {{-- RIGHT: DETAILS --}}
    <div class="col-span-8">

        <div class="panel">
            <div id="member-details">
                <div class="empty-state">
                    <h3>Select one of your listings</h3>
                </div>
            </div>
        </div>

        {{-- TRADES --}}
        <div class="panel mt-6">

            <h3>🔄 My Trades</h3>

            @foreach($trades as $trade)
                <div class="booking-card">

                    <div>
                        <strong>Listing ID:</strong> {{ $trade->listing_id }}
                    </div>

                    <div>
                        <strong>Status:</strong> {{ $trade->status }}
                    </div>

                </div>
            @endforeach

        </div>

        {{-- QUESTIONS --}}
        <div class="panel mt-6">

            <h3>❓ My Questions</h3>

            @foreach($questions as $question)
                <div class="booking-card">

                    <div>
                        <strong>Q:</strong> {{ $question->content }}
                    </div>

                    <div>
                        <strong>Status:</strong> {{ $question->status }}
                    </div>

                    {{-- ANSWERS --}}
                    @foreach($question->answers as $answer)
                        <div class="info-box mt-2">

                            <div>
                                {{ $answer->content }}
                            </div>

                            <form method="POST" action="{{ route('member.answers.accept') }}">
                                @csrf

                                <input type="hidden" name="answer_id" value="{{ $answer->id }}">
                                <input type="hidden" name="question_id" value="{{ $question->id }}">

                                <button class="btn-success">
                                    Accept
                                </button>
                            </form>

                        </div>
                    @endforeach

                </div>
            @endforeach

        </div>

    </div>

</div>

@endsection