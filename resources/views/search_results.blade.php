@extends('layout.main')

@section('main_contents')
<section class="section">
    <div class="container-fluid">
        <div class="title-wrapper pt-30">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="title">
                        <h2>Search Results for: "{{ $query }}"</h2>
                    </div>
                </div>
            </div>
        </div>

        <div id="wishlist-feedback"></div>

        <div class="row g-4 mt-10">
            @forelse ($cards as $card)
                <div class="col-xl-3 col-lg-3 col-sm-6">
                    <div class="card h-100 card-hover">
                        <a href="{{ route('card.detail', $card->id) }}">
                            <img src="{{ asset($card->image_url) }}" class="card-img-top" alt="{{ $card->name }}" 
                                 style="transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title">{{ $card->name }}</h5>
                            <div class="mb-2">
                                <span class="badge bg-secondary">{{ $card->rarity }}</span>
                                <span class="badge bg-info">{{ $card->card_type }}</span>
                            </div>
                            
                            @if($card->estimated_market_price > 0)
                                <p class="mb-2 text-success"><strong>${{ number_format($card->estimated_market_price, 2) }}</strong></p>
                            @endif

                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('card.detail', $card->id) }}" class="btn btn-primary btn-sm">Details</a>
                                
                                <form action="{{ route('wishlist.toggle', $card->id) }}" method="POST" class="wishlist-toggle-form">
                                    @csrf
                                    <button type="submit" 
                                            class="btn {{ in_array($card->id, $wishlistCardIds) ? 'btn-danger' : 'btn-outline-danger' }} btn-sm wishlist-toggle-btn" 
                                            data-card-id="{{ $card->id }}"
                                            data-in-wishlist="{{ in_array($card->id, $wishlistCardIds) ? '1' : '0' }}"
                                            title="{{ in_array($card->id, $wishlistCardIds) ? 'Remove from Wishlist' : 'Add to Wishlist' }}">
                                        <span class="wishlist-icon">
                                            @if(in_array($card->id, $wishlistCardIds))
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart-fill" viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>
                                                </svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart" viewBox="0 0 16 16">
                                                    <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
                                                </svg>
                                            @endif
                                        </span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p>No cards found matching your search.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<style>
.card-hover { transition: box-shadow 0.3s ease; }
.card-hover:hover { box-shadow: 0 8px 16px rgba(0,0,0,0.2); }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const toggleUrl = "{{ url('/wishlist/toggle') }}";
    const feedback = document.getElementById('wishlist-feedback');

    const iconOutline = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart" viewBox="0 0 16 16"><path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/></svg>`;
    const iconFill = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart-fill" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/></svg>`;

    const showMessage = (type, text) => {
        if (!feedback) return;
        feedback.innerHTML = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">${text}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`;
        setTimeout(() => { if(document.querySelector('.alert')) feedback.innerHTML = ''; }, 3000); // Auto hide setelah 3 detik
    };

    document.querySelectorAll('.wishlist-toggle-form').forEach((form) => {
        const button = form.querySelector('.wishlist-toggle-btn');
        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            const cardId = button.dataset.cardId;
            const previousContent = button.innerHTML;
            
            button.disabled = true;
            button.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

            try {
                const response = await fetch(`${toggleUrl}/${cardId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();
                if (!response.ok) throw new Error(data.message || 'Error');

                const inWishlist = data.in_wishlist === true;
                button.dataset.inWishlist = inWishlist ? '1' : '0';
                button.innerHTML = `<span class="wishlist-icon">${inWishlist ? iconFill : iconOutline}</span>`;
                button.title = inWishlist ? 'Remove from Wishlist' : 'Add to Wishlist';
                
                button.classList.toggle('btn-outline-danger', !inWishlist);
                button.classList.toggle('btn-danger', inWishlist);

                showMessage('success', data.message);
            } catch (error) {
                button.innerHTML = previousContent;
                showMessage('danger', error.message);
            } finally {
                button.disabled = false;
            }
        });
    });
});
</script>
@endsection