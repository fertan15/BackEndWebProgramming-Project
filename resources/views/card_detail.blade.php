@extends('layout.main')

@section('main_contents')
<section class="section">
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <div class="title-wrapper pt-30 mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('card_sets') }}">Card Sets</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('set.cards', $card->card_set_id) }}">{{ $card->cardSet->name }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $card->name }}</li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div id="wishlist-feedback" class="mb-3"></div>

            <!-- Left Column: Card Image & Actions -->
            <div class="col-lg-5 col-xl-4">
                <div class="card mb-4">
                    <div class="card-body text-center p-4">
                        <img src="{{ asset($card->image_url) }}" 
                             alt="{{ $card->name }}" 
                             class="img-fluid rounded shadow-sm mb-3"
                             style="max-height: 500px; object-fit: contain;">
                        
                        <h3 class="mb-3">{{ $card->name }}</h3>
                        
                        <div class="d-flex justify-content-center gap-2 mb-3">
                            <span class="badge bg-secondary px-3 py-2">{{ $card->rarity }}</span>
                            <span class="badge bg-info px-3 py-2">{{ $card->card_type }}</span>
                            @if($card->edition)
                                <span class="badge bg-warning px-3 py-2">{{ $card->edition }}</span>
                            @endif
                        </div>

                        @if($card->estimated_market_price > 0)
                            <div class="mb-4">
                                <h2 class="text-success mb-0">
                                    ${{ number_format($card->estimated_market_price, 2) }}
                                </h2>
                                <small class="text-muted">Estimated Market Price</small>
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="d-grid gap-2">
                            <button class="btn btn-success btn-lg" disabled>
                                <i class="lni lni-cart"></i> Buy Now (Coming Soon)
                            </button>
                            
                            <form action="{{ route('wishlist.toggle', $card->id) }}" method="POST" class="wishlist-toggle-form">
                                @csrf
                                <button type="submit" 
                                        class="btn btn-outline-danger w-100 wishlist-toggle-btn" 
                                        data-card-id="{{ $card->id }}"
                                        data-in-wishlist="{{ $isInWishlist ? '1' : '0' }}">
                                    <span class="wishlist-button-label">
                                        @if($isInWishlist)
                                            <i class="lni lni-heart-filled"></i> Remove from Wishlist
                                        @else
                                            <i class="lni lni-heart"></i> Add to Wishlist
                                        @endif
                                    </span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Card Set Info -->
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-subtitle mb-3 text-muted">From Set</h6>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset($card->cardSet->image_url) }}" 
                                 alt="{{ $card->cardSet->name }}" 
                                 style="width: 60px; height: 60px; object-fit: cover;"
                                 class="rounded me-3">
                            <div>
                                <h6 class="mb-1">{{ $card->cardSet->name }}</h6>
                                <small class="text-muted">{{ $card->cardSet->release_date->format('M Y') }}</small>
                            </div>
                        </div>
                        <a href="{{ route('set.cards', $card->card_set_id) }}" class="btn btn-sm btn-outline-primary w-100 mt-3">
                            View All Cards in Set
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Column: Price Chart, Listings, etc. -->
            <div class="col-lg-7 col-xl-8">
                <!-- Price History Chart -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Price History (Last 30 Days)</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="priceChart" height="100"></canvas>
                    </div>
                </div>

                <!-- Active Listings -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Active Listings</h5>
                        <span class="badge bg-primary">{{ $listings->count() }} Available</span>
                    </div>
                    <div class="card-body">
                        @if($listings->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Seller</th>
                                            <th>Condition</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($listings as $listing)
                                        <tr>
                                            <td>
                                                <strong>{{ $listing->seller->username }}</strong>
                                                <br>
                                                <small class="text-muted">Member since {{ $listing->seller->created_at->format('M Y') }}</small>
                                            </td>
                                            <td>
                                                <span class="badge 
                                                    @if($listing->condition_text == 'Mint') bg-success
                                                    @elseif($listing->condition_text == 'Near Mint') bg-info
                                                    @elseif($listing->condition_text == 'Lightly Played') bg-warning
                                                    @else bg-secondary
                                                    @endif
                                                ">
                                                    {{ $listing->condition_text }}
                                                </span>
                                            </td>
                                            <td>
                                                <strong class="text-success">${{ number_format($listing->price, 2) }}</strong>
                                            </td>
                                            <td>{{ $listing->quantity }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-primary" disabled>
                                                    Buy
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info mb-0">
                                No active listings for this card at the moment. Check back later!
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Card Details -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Card Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Name:</strong> {{ $card->name }}</p>
                                <p><strong>Type:</strong> {{ $card->card_type }}</p>
                                <p><strong>Rarity:</strong> {{ $card->rarity }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Edition:</strong> {{ $card->edition ?? 'Standard' }}</p>
                                <p><strong>Set:</strong> {{ $card->cardSet->name }}</p>
                                <p><strong>Card ID:</strong> #{{ $card->id }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Related Cards -->
                @if($relatedCards->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">More from {{ $card->cardSet->name }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @foreach($relatedCards as $related)
                            <div class="col-md-3 col-sm-6">
                                <a href="{{ route('card.detail', $related->id) }}" class="text-decoration-none">
                                    <div class="card card-hover h-100">
                                        <img src="{{ asset($related->image_url) }}" 
                                             class="card-img-top" 
                                             alt="{{ $related->name }}">
                                        <div class="card-body p-2">
                                            <small class="d-block text-dark"><strong>{{ $related->name }}</strong></small>
                                            @if($related->estimated_market_price > 0)
                                                <small class="text-success">${{ number_format($related->estimated_market_price, 2) }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

<style>
.card-hover {
    transition: transform 0.2s, box-shadow 0.2s;
}
.card-hover:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.2);
}
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
// price history chartnya
const ctx = document.getElementById('priceChart').getContext('2d');
const priceChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: @json($priceHistory['dates']),
        datasets: [{
            label: 'Price ($)',
            data: @json($priceHistory['prices']),
            borderColor: 'rgb(54, 162, 235)',
            backgroundColor: 'rgba(54, 162, 235, 0.1)',
            tension: 0.4,
            fill: true,
            pointRadius: 3,
            pointHoverRadius: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                mode: 'index',
                intersect: false,
                callbacks: {
                    label: function(context) {
                        return '$' + context.parsed.y.toFixed(2);
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: false,
                ticks: {
                    callback: function(value) {
                        return '$' + value.toFixed(2);
                    }
                }
            }
        }
    }
});
</script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const toggleUrl = "{{ url('/wishlist/toggle') }}";
    const feedback = document.getElementById('wishlist-feedback');
    const form = document.querySelector('.wishlist-toggle-form');
    const button = document.querySelector('.wishlist-toggle-btn');
    const label = document.querySelector('.wishlist-button-label');

    const showMessage = (type, text) => {
        if (!feedback) return;
        feedback.innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${text}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>`;
    };

    if (form && button && label) {
        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            const cardId = button.dataset.cardId;
            const previousContent = label.innerHTML;
            button.disabled = true;
            label.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';

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

                if (!response.ok) {
                    throw new Error(data.message || 'Unable to update wishlist');
                }

                const inWishlist = data.in_wishlist === true;
                button.dataset.inWishlist = inWishlist ? '1' : '0';
                button.classList.toggle('btn-outline-danger', !inWishlist);
                button.classList.toggle('btn-danger', inWishlist);
                label.innerHTML = inWishlist
                    ? '<i class="lni lni-heart-filled"></i> Remove from Wishlist'
                    : '<i class="lni lni-heart"></i> Add to Wishlist';

                showMessage('success', data.message || 'Wishlist updated');
            } catch (error) {
                label.innerHTML = previousContent;
                showMessage('danger', error.message || 'Something went wrong');
            } finally {
                button.disabled = false;
            }
        });
    }
});
</script>
@endsection