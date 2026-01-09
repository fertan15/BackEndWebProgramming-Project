@extends('layout.main')

@section('main_contents')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <div class="card border-0 shadow-lg overflow-hidden" style="border-radius: 20px;">
                <div class="row g-0">
                    
                    <div class="col-md-5 p-4 p-lg-5 bg-white">
                        <div class="mb-4">
                            @if(isset($listing))
                                <a href="{{ url()->previous() }}" class="btn btn-sm btn-light rounded-pill px-3">
                                    <i class="lni lni-arrow-left"></i> Back
                                </a>
                            @else
                                <a href="{{ url('/cards') }}" class="btn btn-sm btn-light rounded-pill px-3">
                                    <i class="lni lni-arrow-left"></i> Back to Market
                                </a>
                            @endif
                        </div>

                        <div class="mb-4">
                            <span class="badge bg-soft-primary text-primary mb-2">{{ $card->rarity }}</span>
                            <h2 class="fw-bold text-dark">{{ $card->name }}</h2>
                            <p class="text-muted">{{ $card->edition }} Edition</p>
                        </div>

                        <div class="p-4 rounded-4 mb-4" style="background-color: #f8f9fa;">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-secondary">Item Price</span>
                                <span class="fw-bold">
                                    {{ $currencySymbol }}{{ isset($listing) ? number_format((float)$listing->price, 2, ',', '.') : number_format((float)$card->estimated_market_price, 2, ',', '.') }}
                                </span>
                            </div>
                            @if(isset($quantity) && $quantity > 1)
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="text-secondary">Quantity</span>
                                    <span class="fw-bold">{{ $quantity }}</span>
                                </div>
                            @endif
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-secondary">Platform Fee</span>
                                <span class="text-success fw-bold">Free</span>
                            </div>
                            <hr class="my-3" style="border-style: dashed;">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h5 mb-0 fw-bold">Total Amount</span>
                                <span class="h3 mb-0 fw-bolder text-primary">
                                    {{ $currencySymbol }}{{ isset($totalPrice) ? number_format((float)$totalPrice, 2, ',', '.') : (isset($listing) ? number_format((float)$listing->price, 2, ',', '.') : number_format((float)$card->estimated_market_price, 2, ',', '.')) }}
                                </span>
                            </div>
                        </div>

                        <div class="d-flex align-items-center p-3 mb-4 rounded-3 border {{ $currentUser->balance >= (isset($totalPrice) ? $totalPrice : (isset($listing) ? $listing->price : $card->estimated_market_price)) ? 'border-success-subtle bg-success-light' : 'border-danger-subtle bg-danger-light' }}">
                            <div class="flex-shrink-0">
                                <i class="lni lni-wallet h4 mb-0 {{ $currentUser->balance >= (isset($totalPrice) ? $totalPrice : (isset($listing) ? $listing->price : $card->estimated_market_price)) ? 'text-success' : 'text-danger' }}"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="small mb-0 text-muted">Your Current Balance</p>
                                <h6 class="mb-0 fw-bold">
                                    {{ $currencySymbol }}{{ number_format((float)$currentUser->balance, 2, ',', '.') }}
                                </h6>
                            </div>
                        </div>

                        @php
                            $requiredAmount = isset($totalPrice) ? $totalPrice : (isset($listing) ? $listing->price : $card->estimated_market_price);
                        @endphp

                        @if($currentUser->balance >= $requiredAmount)
                            @if(isset($listing))
                                <form id="checkoutForm" method="POST" action="{{ route('buy.listing', $listing->id) }}" onsubmit="return confirmPurchase()">
                                    @csrf
                                    <input type="hidden" name="quantity" value="{{ $quantity ?? 1 }}">
                                    <button type="submit" class="btn btn-primary btn-lg w-100 py-3 shadow fw-bold rounded-pill">
                                        Complete Purchase
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('purchase.process') }}" method="POST" onsubmit="return confirmPurchase()">
                                    @csrf
                                    <input type="hidden" name="card_id" value="{{ $card->id }}">
                                    <button type="submit" class="btn btn-primary btn-lg w-100 py-3 shadow fw-bold rounded-pill">
                                        Complete Purchase
                                    </button>
                                </form>
                            @endif
                        @else
                            <div class="alert alert-danger rounded-4 border-0 mb-3">
                                <small>
                                    <strong>Insufficient Funds:</strong> 
                                    You need {{ $currencySymbol }}{{ number_format((float)$requiredAmount - (float)$currentUser->balance, 2, ',', '.') }} more.
                                </small>
                            </div>
                            <a href="{{ url('/topup') }}" class="btn btn-dark btn-lg w-100 py-3 rounded-pill fw-bold">
                                Top Up Wallet
                            </a>
                        @endif

                        <p class="text-center text-muted small mt-4">
                            <i class="lni lni-shield me-1"></i> Secure Blockchain Transaction
                        </p>
                    </div>

                    <div class="col-md-7 d-flex align-items-center justify-content-center p-5" 
                         style="background: radial-gradient(circle at center, #2b32b2 0%, #141e30 100%); position: relative;">
                        
                        <div class="position-absolute top-0 start-0 w-100 h-100 overflow-hidden" style="opacity: 0.1;">
                            <i class="lni lni-graph position-absolute" style="font-size: 20rem; top: -10%; left: -10%;"></i>
                        </div>

                        <div class="text-center position-relative" style="z-index: 2;">
                            <div class="card-glow"></div>
                            <img src="{{ asset($card->image_url) }}" 
                                 alt="{{ $card->name }}" 
                                 class="img-fluid floating-card shadow-lg" 
                                 style="max-height: 500px; border-radius: 15px;">
                            
                            <div class="mt-4">
                                <span class="text-white-50 text-uppercase tracking-widest small">Authenticity Verified</span>
                                <div class="h5 text-white mt-1">{{ $card->card_type }}</div>
                            </div>
                        </div>
                    </div>

                  </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-soft-primary { background-color: #e7f1ff; }
    .bg-success-light { background-color: #f0fff4; }
    .bg-danger-light { background-color: #fff5f5; }
    
    .floating-card {
        animation: float 6s ease-in-out infinite;
        border: 1px solid rgba(255,255,255,0.2);
    }

    @keyframes float {
        0% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(2deg); }
        100% { transform: translateY(0px) rotate(0deg); }
    }

    .card-glow {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 100%;
        height: 100%;
        background: rgba(0, 123, 255, 0.3);
        filter: blur(80px);
        border-radius: 50%;
        z-index: -1;
    }

    .tracking-widest { letter-spacing: 0.2em; }
</style>

<script>
function confirmPurchase() {
}

// Handle form submission with notification
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('checkoutForm');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';
            
            // Confirm purchase first
            @if(isset($listing))
                let price = "{{ number_format((float)$listing->price, 2, ',', '.') }}";
                let quantity = "{{ $quantity ?? 1 }}";
                let confirmMsg = "Confirm purchase of " + quantity + "x {{ $card->name }} for {{ $currencySymbol }}" + price + "?";
            @else
                let price = "{{ number_format((float)$card->estimated_market_price, 2, ',', '.') }}";
                let confirmMsg = "Confirm purchase of {{ $card->name }} for {{ $currencySymbol }}" + price + "?";
            @endif
            
            if (confirm(confirmMsg)) {
                // Submit the form
                fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json().catch(() => ({ success: true })))
                .then(data => {
                    // Show success notification
                    showSuccessNotification('✓ Purchase Successful!', 'Your card has been added to your inventory.');
                    
                    // Redirect after 2 seconds
                    setTimeout(() => {
                        @if(isset($listing))
                            window.location.href = '{{ route("card.detail", $card->id) }}';
                        @else
                            window.location.href = '/home';
                        @endif
                    }, 2000);
                })
                .catch(error => {
                    console.error('Error:', error);
                    showErrorNotification('Purchase Failed', 'An error occurred. Please try again.');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                });
            } else {
                // User cancelled the confirmation
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });
    }
});

// Success notification function
function showSuccessNotification(title, message) {
    const notificationHTML = `
        <div class="alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3" role="alert" style="z-index: 9999; min-width: 350px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
            <div class="d-flex align-items-center">
                <i class="lni lni-checkmark-circle me-2" style="font-size: 1.5rem;"></i>
                <div>
                    <strong>${title}</strong>
                    <p class="mb-0 small">${message}</p>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    
    document.body.insertAdjacentHTML('afterbegin', notificationHTML);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert-success');
        alerts.forEach(alert => {
            alert.classList.remove('show');
            setTimeout(() => alert.remove(), 150);
        });
    }, 5000);
}

// Error notification function
function showErrorNotification(title, message) {
    const notificationHTML = `
        <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3" role="alert" style="z-index: 9999; min-width: 350px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
            <div class="d-flex align-items-center">
                <i class="lni lni-close-circle me-2" style="font-size: 1.5rem;"></i>
                <div>
                    <strong>${title}</strong>
                    <p class="mb-0 small">${message}</p>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    
    document.body.insertAdjacentHTML('afterbegin', notificationHTML);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert-danger');
        alerts.forEach(alert => {
            alert.classList.remove('show');
            setTimeout(() => alert.remove(), 150);
        });
    }, 5000);
}
</script>
@endsection