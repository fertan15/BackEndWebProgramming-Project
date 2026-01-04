@extends('layout.main')

@section('main_contents')
    <section class="section">
        <div class="container-fluid">
            <div class="title-wrapper pt-30">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="title">
                            <strong>
                                <h2>
                                    @if(isset($cardSet))
                                        {{ $cardSet->name }} - Cards
                                    @else
                                        All Pokemon Cards
                                    @endif
                                </h2>
                            </strong>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="breadcrumb-wrapper">
                            <strong>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item">
                                            <a href="{{ route('home') }}">Home</a>
                                        </li>
                                        <li class="breadcrumb-item">
                                            <a href="{{ route('card_sets') }}">Card Sets</a>
                                        </li>
                                        <li class="breadcrumb-item active" aria-current="page">
                                            Cards
                                        </li>
                                    </ol>
                                </nav>
                            </strong>
                        </div>
                    </div>
                </div>
            </div>

            @if(isset($cardSet))
                <div class="alert alert-info mb-4">
                    <strong>{{ $cardSet->name }}</strong> - {{ $cardSet->description }}
                </div>
            @endif

            <div class="row g-4">
                @forelse ($cards as $card)
                    <div class="col-xl-3 col-lg-3 col-sm-6">
                        <div class="card h-100 card-hover">
                            <!-- Clickable card image -->
                            <a href="{{ route('card.detail', $card->id) }}" class="text-decoration-none">
                                <img src="{{ asset($card->image_url) }}" 
                                     class="card-img-top" 
                                     alt="{{ $card->name }}"
                                     style="cursor: pointer; transition: transform 0.2s;"
                                     onmouseover="this.style.transform='scale(1.05)'"
                                     onmouseout="this.style.transform='scale(1)'">
                            </a>
                            <div class="card-body">
                                <!-- Clickable card name -->
                                <a href="{{ route('card.detail', $card->id) }}" class="text-decoration-none">
                                    <h5 class="card-title text-dark">{{ $card->name }}</h5>
                                </a>
                                
                                <div class="mb-2">
                                    <span class="badge bg-secondary">{{ $card->rarity }}</span>
                                    <span class="badge bg-info">{{ $card->card_type }}</span>
                                </div>

                                @if($card->estimated_market_price > 0)
                                    <p class="mb-2">
                                        <strong class="text-success">${{ number_format($card->estimated_market_price, 2) }}</strong>
                                    </p>
                                @endif

                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('card.detail', $card->id) }}" class="btn btn-primary btn-sm">
                                        View Details
                                    </a>
                                    <form action="{{ route('wishlist.toggle', $card->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger btn-sm" 
                                                title="{{ in_array($card->id, $wishlistCardIds) ? 'Remove from Wishlist' : 'Add to Wishlist' }}">
                                            @if(in_array($card->id, $wishlistCardIds))
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart-fill" viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>
                                                </svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart" viewBox="0 0 16 16">
                                                    <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
                                                </svg>
                                            @endif
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-warning">
                            No cards found in this set.
                        </div>
                    </div>
                @endforelse
            </div>
            <br>
        </div>
    </section>
@endsection

<style>
.card-hover {
    transition: box-shadow 0.3s ease;
}

.card-hover:hover {
    box-shadow: 0 8px 16px rgba(0,0,0,0.2);
}
</style>