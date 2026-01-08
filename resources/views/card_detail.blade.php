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
                        <li class="breadcrumb-item"><a
                                href="{{ route('set.cards', $card->card_set_id) }}">{{ $card->cardSet->name }}</a></li>
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
                            <img src="{{ asset($card->image_url) }}" alt="{{ $card->name }}"
                                class="img-fluid rounded shadow-sm mb-3" style="max-height: 500px; object-fit: contain;">

                            <h3 class="mb-3">{{ $card->name }}</h3>

                            <div class="d-flex justify-content-center gap-2 mb-3">
                                <span class="badge bg-secondary px-3 py-2">{{ $card->rarity }}</span>
                                <span class="badge bg-info px-3 py-2">{{ $card->card_type }}</span>
                                @if ($card->edition)
                                    <span class="badge bg-warning px-3 py-2">{{ $card->edition }}</span>
                                @endif
                            </div>

                            @if ($card->estimated_market_price > 0)
                                <div class="mb-4">
                                    <h2 class="text-success mb-0">
                                        ${{ number_format($card->estimated_market_price, 2) }}
                                    </h2>
                                    <small class="text-muted">Estimated Market Price</small>
                                </div>
                            @endif

                            <!-- Action Buttons -->
                            <div class="d-grid gap-2">
                                {{-- <form action="{{ route('checkout.show') }}" method="GET">
                                <input type="hidden" name="card_id" value="{{ $card->id }}">
                                
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="lni lni-cart"></i> Buy Now
                                </button>
                            </form> --}}

                                <form action="{{ route('wishlist.toggle', $card->id) }}" method="POST"
                                    class="wishlist-toggle-form">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger w-100 wishlist-toggle-btn"
                                        data-card-id="{{ $card->id }}"
                                        data-in-wishlist="{{ $isInWishlist ? '1' : '0' }}">
                                        <span class="wishlist-button-label">
                                            @if ($isInWishlist)
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
                                <img src="{{ asset($card->cardSet->image_url) }}" alt="{{ $card->cardSet->name }}"
                                    style="width: 60px; height: 60px; object-fit: cover;" class="rounded me-3">
                                <div>
                                    <h6 class="mb-1">{{ $card->cardSet->name }}</h6>
                                    <small class="text-muted">{{ $card->cardSet->release_date->format('M Y') }}</small>
                                </div>
                            </div>
                            <a href="{{ route('set.cards', $card->card_set_id) }}"
                                class="btn btn-sm btn-outline-primary w-100 mt-3">
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

                    <!-- Active Listings & Add Listings-->
                    <div class="card mb-4">
                        <div class="card-header d-flex align-items-center">
                            <h5 class="mb-0">Active Listings</h5>

                            <div class="ms-auto d-flex align-items-center gap-2">
                                <span class="badge bg-primary">
                                    {{ $listings->count() }} Available
                                </span>

                                @if (session()->get('user_id'))
                                    @php
                                        $userHasListing = $listings->contains('seller_id', session()->get('user_id'));
                                    @endphp
                                    <button type="button" class="btn btn-primary btn-sm" 
                                        data-bs-toggle="modal"
                                        data-bs-target="#exampleModal"
                                        @if($userHasListing) disabled @endif>
                                        {{ $userHasListing ? 'Listed' : 'Add Listings' }}
                                    </button>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            @if ($listings->count() > 0)
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
                                            @foreach ($listings as $listing)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $listing->seller->username }}</strong>
                                                        <br>
                                                        <small class="text-muted">Member since
                                                            {{ $listing->seller->created_at->format('M Y') }}</small>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge 
                                                    @if ($listing->condition_text == 'Mint') bg-success
                                                    @elseif($listing->condition_text == 'Near Mint') bg-info
                                                    @elseif($listing->condition_text == 'Lightly Played') bg-warning
                                                    @else bg-secondary @endif
                                                ">
                                                            {{ $listing->condition_text }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <strong
                                                            class="text-success">${{ number_format($listing->price, 2) }}</strong>
                                                    </td>
                                                    <td>{{ $listing->quantity }}</td>
                                                    <td>
                                                        @if ($listing->seller_id == session()->get('user_id'))
                                                            <form method="POST" action="/cancel-listing/{{ $listing->id }}" style="display: inline;">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to cancel this listing?')">
                                                                    <i class="lni lni-trash"></i> Cancel
                                                                </button>
                                                            </form>
                                                        @else
                                                            <button class="btn btn-sm btn-primary buy-btn" data-listing-id="{{ $listing->id }}" data-card-name="{{ $card->name }}" data-price="{{ $listing->price }}" data-quantity="{{ $listing->quantity }}">
                                                                Buy
                                                            </button>
                                                        @endif
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

                    {{-- Listings History --}}
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">History Listings</h5>
                        </div>
                        <div class="card-body">
                            @if ($history->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Seller</th>
                                                <th>Buyer</th>
                                                <th>Purchased On</th>
                                                <th>Quantity</th>
                                                <th>Price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($history as $historys)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $historys->listing->seller->username }}</strong>
                                                        <br>
                                                    </td>
                                                    <td>
                                                        @if ($historys->buyer)
                                                            <strong>{{ $historys->buyer->username }}</strong>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($historys->purchased_at)
                                                            {{ \Carbon\Carbon::parse($historys->purchased_at)->format('M d, Y H:i') }}
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $historys->quantity }}</td>
                                                    <td>
                                                        <strong
                                                            class="text-success">${{ number_format($historys->price_at_purchase, 2) }}</strong>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info mb-0">
                                    No history listings for this card at the moment.
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
                    @if ($relatedCards->count() > 0)
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">More from {{ $card->cardSet->name }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    @foreach ($relatedCards as $related)
                                        <div class="col-md-3 col-sm-6">
                                            <a href="{{ route('card.detail', $related->id) }}"
                                                class="text-decoration-none">
                                                <div class="card card-hover h-100">
                                                    <img src="{{ asset($related->image_url) }}" class="card-img-top"
                                                        alt="{{ $related->name }}">
                                                    <div class="card-body p-2">
                                                        <small
                                                            class="d-block text-dark"><strong>{{ $related->name }}</strong></small>
                                                        @if ($related->estimated_market_price > 0)
                                                            <small
                                                                class="text-success">${{ number_format($related->estimated_market_price, 2) }}</small>
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
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
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
                    label.innerHTML =
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';

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
                        label.innerHTML = inWishlist ?
                            '<i class="lni lni-heart-filled"></i> Remove from Wishlist' :
                            '<i class="lni lni-heart"></i> Add to Wishlist';

                        showMessage('success', data.message || 'Wishlist updated');
                    } catch (error) {
                        label.innerHTML = previousContent;
                        showMessage('danger', error.message || 'Something went wrong');
                    } finally {
                        button.disabled = false;
                    }
                });
            }

            // Buy listing functionality
            const buyButtons = document.querySelectorAll('.buy-btn');
            const buyModal = new bootstrap.Modal(document.getElementById('buyModal'));
            const buyForm = document.getElementById('buyForm');
            const buyQuantity = document.getElementById('buyQuantity');
            const modalCardName = document.getElementById('modalCardName');
            const modalPrice = document.getElementById('modalPrice');
            const totalPriceEl = document.getElementById('totalPrice');
            const balanceInfo = document.getElementById('balanceInfo');
            const decreaseQtyBtn = document.getElementById('decreaseQty');
            const increaseQtyBtn = document.getElementById('increaseQty');
            let currentListingId = null;
            let currentPrice = 0;
            let maxQuantity = 0;

            const formatCurrency = (value) => {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(value);
            };

            const updateTotal = () => {
                const qty = parseInt(buyQuantity.value) || 1;
                const total = currentPrice * qty;
                totalPriceEl.textContent = formatCurrency(total);
            };

            buyQuantity.addEventListener('change', updateTotal);
            buyQuantity.addEventListener('input', updateTotal);

            decreaseQtyBtn.addEventListener('click', () => {
                const current = parseInt(buyQuantity.value) || 1;
                if (current > 1) {
                    buyQuantity.value = current - 1;
                    updateTotal();
                }
            });

            increaseQtyBtn.addEventListener('click', () => {
                const current = parseInt(buyQuantity.value) || 1;
                if (current < maxQuantity) {
                    buyQuantity.value = current + 1;
                    updateTotal();
                } else {
                    alert('Maximum available quantity reached!');
                }
            });

            buyButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    console.log('Buy button clicked!');
                    currentListingId = btn.dataset.listingId;
                    const cardName = btn.dataset.cardName;
                    currentPrice = parseFloat(btn.dataset.price);
                    maxQuantity = parseInt(btn.dataset.quantity);

                    console.log('Listing ID:', currentListingId, 'Card:', cardName, 'Price:', currentPrice, 'Max Qty:', maxQuantity);

                    modalCardName.textContent = cardName;
                    modalPrice.textContent = formatCurrency(currentPrice);
                    buyQuantity.value = 1;
                    buyQuantity.max = maxQuantity;
                    balanceInfo.textContent = 'Your current balance: Rp ' + ({{ session()->get('user_id') ? auth()->user()->balance ?? 0 : 0 }}).toLocaleString('id-ID');
                    
                    updateTotal();
                    console.log('Opening buy modal');
                    buyModal.show();
                });
            });

            buyForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                console.log('Buy form submitted');

                const quantity = parseInt(buyQuantity.value);
                const submitBtn = document.getElementById('submitBuyBtn');
                const originalBtnText = submitBtn.innerHTML;

                console.log('Submitting purchase - Listing ID:', currentListingId, 'Quantity:', quantity);

                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Processing...';

                try {
                    console.log('Fetching /buy-listing/' + currentListingId);
                    const response = await fetch(`/buy-listing/${currentListingId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            quantity: quantity
                        })
                    });

                    // Log response for debugging
                    console.log('Response status:', response.status);
                    console.log('Response ok:', response.ok);
                    const data = await response.json();
                    console.log('Response data:', data);

                    // Check if response is successful
                    if (response.ok) {
                        // Show success message before reload
                        showMessage('success', data.message || 'Purchase successful! Adding card to your inventory...');
                        setTimeout(() => {
                            window.location.href = '{{ url('/cards/' . $card->id) }}';
                        }, 1500);
                    } else {
                        throw new Error(data.error || 'Purchase failed');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showMessage('danger', error.message || 'Purchase failed. Please try again.');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                }
            });
        });
    </script>


    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Sell Your Item</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="/savelistings">
                    {{-- harus login dlu biar bisa add listings --}}
                    @csrf
                    <!-- hidden field -->
                    <input type="hidden" name="cardid" value="{{ $card->id }}">
                    <input type="hidden" name="sellerid" value="{{ session()->get('user_id') }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Condition</label>
                            <select class="form-select" name="condition" required>
                                <option value="Mint">Mint</option>
                                <option value="Near Mint">Near Mint</option>
                                <option value="Lightly Played">Lightly Played</option>
                                <option value="Played">Played</option>
                                <option value="Heavily Played">Heavily Played</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Price ($)</label>
                            <input type="number" name="price" class="form-control" step="0.01" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Quantity</label>
                            <input type="number" name="quantity" class="form-control" min="1" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add Listings</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Buy Modal -->
    <div class="modal fade" id="buyModal" tabindex="-1" aria-labelledby="buyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="buyModalLabel">Purchase Card</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="buyForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Card</label>
                            <p class="form-control-plaintext"><strong id="modalCardName"></strong></p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Unit Price</label>
                            <p class="form-control-plaintext"><strong id="modalPrice"></strong></p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Quantity</label>
                            <div class="input-group">
                                <button class="btn btn-outline-secondary" type="button" id="decreaseQty">-</button>
                                <input type="number" id="buyQuantity" name="quantity" class="form-control text-center" value="1" min="1" required>
                                <button class="btn btn-outline-secondary" type="button" id="increaseQty">+</button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Total Price</label>
                            <h3 class="text-success"><strong id="totalPrice">Rp 0</strong></h3>
                        </div>

                        <div class="alert alert-info" role="alert">
                            <small id="balanceInfo"></small>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success" id="submitBuyBtn">
                            <i class="lni lni-cart"></i> Confirm Purchase
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

<style>
    /* Checkout animations */
    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes scaleIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
    }

    .modal-content {
        animation: slideInDown 0.3s ease-out;
    }

    .btn-primary:active {
        animation: pulse 0.3s ease-out;
    }

    .alert {
        animation: slideInDown 0.4s ease-out;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
        transition: background-color 0.2s ease;
    }
</style>
