@extends('layout.main')

@section('main_contents')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <div class="card border-0 shadow-lg overflow-hidden" style="border-radius: 20px;">
                <div class="row g-0">
                    
                    <div class="col-md-5 p-4 p-lg-5 bg-white">
                        <div class="mb-4">
                            <a href="{{ url('/cards') }}" class="btn btn-sm btn-light rounded-pill px-3">
                                <i class="lni lni-arrow-left"></i> Back to Market
                            </a>
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
                                    {{ $currencySymbol }}{{ number_format((float)$card->estimated_market_price, 2, ',', '.') }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-secondary">Platform Fee</span>
                                <span class="text-success fw-bold">Free</span>
                            </div>
                            <hr class="my-3" style="border-style: dashed;">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h5 mb-0 fw-bold">Total Amount</span>
                                <span class="h3 mb-0 fw-bolder text-primary">
                                    {{ $currencySymbol }}{{ number_format((float)$card->estimated_market_price, 2, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <div class="d-flex align-items-center p-3 mb-4 rounded-3 border {{ $currentUser->balance >= $card->estimated_market_price ? 'border-success-subtle bg-success-light' : 'border-danger-subtle bg-danger-light' }}">
                            <div class="flex-shrink-0">
                                <i class="lni lni-wallet h4 mb-0 {{ $currentUser->balance >= $card->estimated_market_price ? 'text-success' : 'text-danger' }}"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="small mb-0 text-muted">Your Current Balance</p>
                                <h6 class="mb-0 fw-bold">
                                    {{ $currencySymbol }}{{ number_format((float)$currentUser->balance, 2, ',', '.') }}
                                </h6>
                            </div>
                        </div>

                        @if($currentUser->balance >= $card->estimated_market_price)
                            <form action="{{ route('purchase.process') }}" method="POST" onsubmit="return confirmPurchase()">
                                @csrf
                                <input type="hidden" name="card_id" value="{{ $card->id }}">
                                <button type="submit" class="btn btn-primary btn-lg w-100 py-3 shadow fw-bold rounded-pill">
                                    Complete Purchase
                                </button>
                            </form>
                        @else
                            <div class="alert alert-danger rounded-4 border-0 mb-3">
                                <small>
                                    <strong>Insufficient Funds:</strong> 
                                    You need {{ $currencySymbol }}{{ number_format((float)$card->estimated_market_price - (float)$currentUser->balance, 2, ',', '.') }} more.
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
                            <img src="{{ $card->image_url }}" 
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
     // Updated to show decimals in the alert
     let price = "{{ number_format((float)$card->estimated_market_price, 2, ',', '.') }}";
     return confirm("Confirm purchase of {{ $card->name }} for {{ $currencySymbol }}" + price + "?");
}
</script>
@endsection