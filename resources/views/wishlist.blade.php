@extends('layout.main')

@section('main_contents')
    <section class="section">
        <div class="container-fluid">
            <div class="title-wrapper pt-30">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="title">
                            <strong>
                                <h2>My Wishlist</h2>
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
                                        <li class="breadcrumb-item active" aria-current="page">
                                            Wishlist
                                        </li>
                                    </ol>
                                </nav>
                            </strong>
                        </div>
                    </div>
                </div>
            </div>
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($wishlistItems->isEmpty())
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" class="bi bi-heart text-muted mb-3" viewBox="0 0 16 16">
                                    <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
                                </svg>
                                <h4 class="text-muted">Your wishlist is empty</h4>
                                <p class="text-muted">Start adding cards to your wishlist to see them here!</p>
                                <a href="{{ route('cards') }}" class="btn btn-primary mt-3">Browse Cards</a>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="row g-4">
                    @foreach ($wishlistItems as $item)
                        <div class="col-xl-3 col-lg-3 col-sm-6">
                            <div class="card">
                                <img src="{{ asset('images/cards/' . $item->card->image_url) }}" class="card-img-top" alt="{{ $item->card->name }}">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $item->card->name }}</h5>
                                    <p class="card-text">
                                        <small class="text-muted">Rarity: {{ $item->card->rarity }}</small><br>
                                        <small class="text-muted">Type: {{ $item->card->card_type }}</small><br>
                                        @if($item->card->estimated_market_price)
                                            <strong class="text-primary">${{ number_format($item->card->estimated_market_price, 2) }}</strong>
                                        @endif
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href="#" class="btn btn-primary btn-sm">Trade</a>
                                        <form action="{{ route('wishlist.toggle', $item->card->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Remove from Wishlist">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6Z"/>
                                                    <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1ZM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118ZM2.5 3h11V2h-11v1Z"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                    <small class="text-muted d-block mt-2">Added: {{ $item->added_at->format('M d, Y') }}</small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
            <br>
        </div>
    </section>
@endsection
