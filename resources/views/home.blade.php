@extends('layout.main')

@section('main_contents')
    @if (session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert" style="margin: 20px auto; max-width: 1200px;">
            <strong>⚠️ Warning:</strong> {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <section class="section" style="width: 100%;">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="pokemon-jumbo-xl mb-5">
                        <div class="pokemon-jumbo-xl__overlay"></div>
                        <div class="pokemon-jumbo-xl__glow"></div>

                        <div class="pokemon-jumbo-xl__content">
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                                <span class="pokemon-badge-xl">POKÉMON TRADING CARD GAME</span>
                                <span class="pokemon-chip-xl">SV</span>
                                <span class="pokemon-chip-xl">PF</span>
                                <span class="pokemon-chip-xl">ME</span>
                            </div>

                            <h1 class="pokemon-jumbo-xl__title">Pokemon Card Collection</h1>
                            <p class="pokemon-jumbo-xl__subtitle">
                                Explore, collect, and trade — tampilkan koleksi kamu dengan style yang “TCG banget”.
                            </p>

                            <div class="d-flex flex-wrap gap-3 mt-4">
                                <a href="{{ route('card_sets') }}" class="btn btn-warning pokemon-btn-xl">
                                    View Card Sets
                                </a>
                                <a href="{{ route('cards') }}" class="btn btn-outline-light pokemon-btn-outline-xl">
                                    Explore Cards
                                </a>
                            </div>
                        </div>

                        {{-- dekorasi mewah + animasi --}}
                        <div class="pokeball-xl pokeball-xl--1"></div>
                        <div class="pokeball-xl pokeball-xl--2"></div>
                        <div class="spark spark--1"></div>
                        <div class="spark spark--2"></div>
                        <div class="spark spark--3"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Marketplace Section --}}
    <section class="py-12 bg-gray-50">
        <div class="container"> 
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-end mb-5">
                        <div>
                            <span class="text-primary fw-bold text-uppercase mb-2 d-block" style="font-size: 0.75rem; letter-spacing: 0.05em;">Marketplace</span>
                            <h2 class="h3 fw-extrabold text-dark">Fresh Pulls & Listings</h2>
                        </div>
                        <a href="{{ route('cards') }}" class="btn btn-link text-decoration-none fw-semibold">
                            View All &rarr;
                        </a>
                    </div>

                    {{-- ini ajaxnya yang ngerefresh tiap 30 detik --}}
                    <div id="latest-listings-container" class="row g-4">
                        @include('partials.listing_cards', ['listings' => $latestListings])
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Platform Statistics Section --}}
    <section class="py-5 bg-white">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    <span class="text-primary fw-bold text-uppercase mb-2 d-block" style="font-size: 0.75rem; letter-spacing: 0.05em;">Platform Analytics</span>
                    <h2 class="h3 fw-extrabold text-dark">Platform Statistics</h2>
                    <p class="text-muted">Real-time insights from our marketplace</p>
                </div>
            </div>

            {{-- Overview Stats Cards --}}
            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <i class="lni lni-dollar" style="font-size: 2.5rem; color: #28a745;"></i>
                            </div>
                            <h3 class="fw-bold text-success mb-2">${{ number_format($totalRevenue, 2) }}</h3>
                            <p class="text-muted mb-0">Total Platform Revenue</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <i class="lni lni-cart" style="font-size: 2.5rem; color: #17a2b8;"></i>
                            </div>
                            <h3 class="fw-bold text-info mb-2">{{ number_format($totalTransactions) }}</h3>
                            <p class="text-muted mb-0">Total Transactions</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <i class="lni lni-list" style="font-size: 2.5rem; color: #ffc107;"></i>
                            </div>
                            <h3 class="fw-bold text-warning mb-2">{{ number_format($activeListings) }}</h3>
                            <p class="text-muted mb-0">Active Listings</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Top Performers --}}
            <div class="row g-4">
                {{-- Top Seller --}}
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-gradient-success text-white border-0">
                            <h5 class="mb-0">
                                <i class="lni lni-crown"></i> Top Seller
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($topSeller)
                                <div class="text-center">
                                    <div class="mb-3">
                                        <div class="avatar-circle mx-auto" style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem; font-weight: bold;">
                                            {{ strtoupper(substr($topSeller->name, 0, 2)) }}
                                        </div>
                                    </div>
                                    <h5 class="fw-bold mb-1">{{ $topSeller->name }}</h5>
                                    <p class="text-muted small mb-3">@<span>{{ $topSeller->username }}</span></p>
                                    <div class="bg-light rounded p-3">
                                        <p class="text-muted small mb-1">Total Revenue</p>
                                        <h4 class="fw-bold text-success mb-0">${{ number_format($topSeller->total_revenue, 2) }}</h4>
                                    </div>
                                </div>
                            @else
                                <div class="text-center text-muted py-4">
                                    <p>No sales data yet</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Top Buyer --}}
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-gradient-primary text-white border-0">
                            <h5 class="mb-0">
                                <i class="lni lni-shopping-basket"></i> Top Buyer
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($topBuyer)
                                <div class="text-center">
                                    <div class="mb-3">
                                        <div class="avatar-circle mx-auto" style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem; font-weight: bold;">
                                            {{ strtoupper(substr($topBuyer->name, 0, 2)) }}
                                        </div>
                                    </div>
                                    <h5 class="fw-bold mb-1">{{ $topBuyer->name }}</h5>
                                    <p class="text-muted small mb-3">@<span>{{ $topBuyer->username }}</span></p>
                                    <div class="bg-light rounded p-3">
                                        <p class="text-muted small mb-1">Total Spent</p>
                                        <h4 class="fw-bold text-primary mb-0">${{ number_format($topBuyer->total_spent, 2) }}</h4>
                                    </div>
                                </div>
                            @else
                                <div class="text-center text-muted py-4">
                                    <p>No purchase data yet</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Most Traded Card --}}
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-gradient-warning text-white border-0">
                            <h5 class="mb-0">
                                <i class="lni lni-star-filled"></i> Most Traded Card
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($mostTradedCard)
                                <div class="text-center">
                                    <div class="mb-3">
                                        <img src="{{ asset($mostTradedCard->image_url) }}" alt="{{ $mostTradedCard->name }}" class="img-fluid rounded shadow-sm" style="max-height: 150px; object-fit: contain;">
                                    </div>
                                    <h5 class="fw-bold mb-2">{{ $mostTradedCard->name }}</h5>
                                    <div class="bg-light rounded p-3">
                                        <p class="text-muted small mb-1">Total Traded</p>
                                        <h4 class="fw-bold text-warning mb-0">{{ number_format($mostTradedCard->total_traded) }} <span class="small">units</span></h4>
                                    </div>
                                </div>
                            @else
                                <div class="text-center text-muted py-4">
                                    <p>No trading data yet</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- User's Personal Analytics Section --}}
    @if($userStats)
    <section class="py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12 text-white">
                    <span class="fw-bold text-uppercase mb-2 d-block" style="font-size: 0.75rem; letter-spacing: 0.05em; opacity: 0.9;">Your Analytics</span>
                    <h2 class="h3 fw-extrabold">Your Trading Performance</h2>
                    <p class="mb-0" style="opacity: 0.9;">Track your buying and selling activity</p>
                </div>
            </div>

            {{-- User Stats Cards --}}
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow h-100">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <i class="lni lni-revenue" style="font-size: 2rem; color: #28a745;"></i>
                            </div>
                            <h4 class="fw-bold text-success mb-1">${{ number_format($userStats['salesRevenue'], 2) }}</h4>
                            <p class="text-muted small mb-0">Sales Revenue</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow h-100">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <i class="lni lni-wallet" style="font-size: 2rem; color: #dc3545;"></i>
                            </div>
                            <h4 class="fw-bold text-danger mb-1">${{ number_format($userStats['purchasesSpent'], 2) }}</h4>
                            <p class="text-muted small mb-0">Total Spent</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow h-100">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <i class="lni lni-package" style="font-size: 2rem; color: #ffc107;"></i>
                            </div>
                            <h4 class="fw-bold text-warning mb-1">{{ number_format($userStats['activeListings']) }}</h4>
                            <p class="text-muted small mb-0">Active Listings</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow h-100">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <i class="lni lni-stats-up" style="font-size: 2rem; color: #17a2b8;"></i>
                            </div>
                            <h4 class="fw-bold text-info mb-1">{{ number_format($userStats['totalSales'] + $userStats['totalPurchases']) }}</h4>
                            <p class="text-muted small mb-0">Total Transactions</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- User Activity Details --}}
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow h-100">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3">
                                <i class="lni lni-shopping-basket text-primary"></i> Buying Activity
                            </h5>
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                <span class="text-muted">Total Purchases</span>
                                <span class="fw-bold">{{ number_format($userStats['totalPurchases']) }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Total Amount Spent</span>
                                <span class="fw-bold text-danger">${{ number_format($userStats['purchasesSpent'], 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow h-100">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3">
                                <i class="lni lni-revenue text-success"></i> Selling Activity
                            </h5>
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                <span class="text-muted">Total Sales</span>
                                <span class="fw-bold">{{ number_format($userStats['totalSales']) }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Total Revenue</span>
                                <span class="fw-bold text-success">${{ number_format($userStats['salesRevenue'], 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- User's Most Sold Card --}}
            @if($userStats['mostSoldCard'])
            <div class="row g-4 mt-2">
                <div class="col-12">
                    <div class="card border-0 shadow">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4">
                                <i class="lni lni-star-filled text-warning"></i> Your Best Seller
                            </h5>
                            <div class="row align-items-center">
                                <div class="col-md-3 text-center">
                                    <img src="{{ asset($userStats['mostSoldCard']->image_url) }}" alt="{{ $userStats['mostSoldCard']->name }}" class="img-fluid rounded shadow-sm" style="max-height: 200px; object-fit: contain;">
                                </div>
                                <div class="col-md-9">
                                    <h4 class="fw-bold mb-2">{{ $userStats['mostSoldCard']->name }}</h4>
                                    <p class="text-muted mb-3">This is your most sold card</p>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="bg-light rounded p-3">
                                                <p class="text-muted small mb-1">Units Sold</p>
                                                <h3 class="fw-bold text-primary mb-0">{{ number_format($userStats['mostSoldCard']->total_sold) }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>
    @endif

@endsection

<style>

    .holo-sheen {
    background: linear-gradient(
        105deg,
        transparent 20%,
        rgba(255, 255, 255, 0.4) 45%,
        rgba(100, 200, 255, 0.2) 50%,
        rgba(255, 255, 255, 0.4) 55%,
        transparent 80%
    );
    z-index: 10;
    filter: brightness(1.2) contrast(1.1);
    mix-blend-mode: overlay;
    }

    /* ====== HERO XL ====== */
    .pokemon-jumbo-xl {
        position: relative;
        overflow: hidden;
        border-radius: 26px;
        padding: 64px 56px;
        min-height: 380px;
        border: 1px solid rgba(255, 255, 255, 0.20);
        box-shadow:
            0 18px 55px rgba(0, 0, 0, 0.55),
            inset 0 1px 0 rgba(255, 255, 255, 0.20);
        transform: translateZ(0);
    }

    .pokemon-jumbo-xl__overlay {
        position: absolute;
        inset: 0;
        background:
            radial-gradient(1200px 500px at 20% 20%, #07B9CE, transparent 55%),
            radial-gradient(900px 450px at 80% 30%, #3969E7, transparent 55%),
            linear-gradient(90deg, rgba(125, 42, 231, 0.82), rgba(125, 42, 231, 0.35));
        backdrop-filter: blur(3px);
    }

    /* glow layer yang bergerak biar “premium” */
    .pokemon-jumbo-xl__glow {
        position: absolute;
        inset: -40%;
        background: conic-gradient(from 180deg,
                rgba(7, 185, 206, 0.00),
                rgba(7, 185, 206, 0.18),
                rgba(57, 105, 231, 0.16),
                rgba(125, 42, 231, 0.10),
                rgba(125, 42, 231, 0.00));
        filter: blur(18px);
        opacity: .75;
        animation: glowSpin 9s linear infinite;
        z-index: 1;
    }

    @keyframes glowSpin {
        0% {
            transform: rotate(0deg) scale(1.02);
        }

        100% {
            transform: rotate(360deg) scale(1.02);
        }
    }

    .pokemon-jumbo-xl__content {
        position: relative;
        z-index: 3;
        color: #fff;
        max-width: 860px;
    }

    .pokemon-jumbo-xl__title {
        color: #F0EEE9; /* color of the year 2026 wokowkwko */
        font-weight: 900;
        font-size: clamp(38px, 4vw, 62px);
        letter-spacing: .6px;
        margin: 0;
        text-shadow:
            0 4px 0 rgba(0, 0, 0, 0.40),
            0 14px 35px rgba(0, 0, 0, 0.55);
    }

    .pokemon-jumbo-xl__subtitle {
        margin-top: 16px;
        margin-bottom: 0;
        font-size: 18px;
        line-height: 1.6;
        opacity: .92;
        max-width: 720px;
    }

    /* ====== BADGE & CHIPS ====== */
    .pokemon-badge-xl {
        display: inline-flex;
        align-items: center;
        padding: 10px 16px;
        border-radius: 999px;
        font-weight: 900;
        font-size: 12px;
        letter-spacing: 1px;
        background: rgba(255, 203, 5, 0.96);
        color: #141414;
        border: 1px solid rgba(0, 0, 0, 0.18);
        box-shadow: 0 12px 26px rgba(0, 0, 0, 0.35);
    }

    .pokemon-chip-xl {
        display: inline-flex;
        align-items: center;
        padding: 9px 14px;
        border-radius: 999px;
        font-weight: 850;
        font-size: 12px;
        background: rgba(255, 255, 255, 0.12);
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.20);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.18);
        backdrop-filter: blur(6px);
    }

    /* ====== BUTTONS ====== */
    .pokemon-btn-xl {
        font-weight: 900;
        border-radius: 16px;
        padding: 14px 18px;
        min-width: 170px;
        box-shadow: 0 18px 35px rgba(0, 0, 0, 0.40);
        transform: translateY(0);
        transition: transform .18s ease, box-shadow .18s ease;
    }

    .pokemon-btn-xl:hover {
        transform: translateY(-3px);
        box-shadow: 0 26px 45px rgba(0, 0, 0, 0.55);
    }

    .pokemon-btn-outline-xl {
        font-weight: 900;
        border-radius: 16px;
        padding: 14px 18px;
        min-width: 170px;
        border-width: 2px;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.18);
        transition: transform .18s ease, background-color .18s ease;
    }

    .pokemon-btn-outline-xl:hover {
        transform: translateY(-3px);
        background-color: rgba(255, 255, 255, 0.08);
    }

    /* ====== POKEBALL DECOR ====== */
    .pokeball-xl {
        position: absolute;
        border-radius: 50%;
        z-index: 2;
        opacity: .35;
        background:
            radial-gradient(circle at 50% 50%, #fff 0 20px, transparent 21px),
            linear-gradient(#e53935 0 50%, #ffffff 50% 52%, #141414 52% 56%, #ffffff 56% 100%);
        box-shadow: 0 22px 50px rgba(0, 0, 0, 0.55);
        animation: floatyXL 4.8s ease-in-out infinite;
    }

    .pokeball-xl--1 {
        width: 190px;
        height: 190px;
        right: -55px;
        top: -55px;
        transform: rotate(-10deg);
    }

    .pokeball-xl--2 {
        width: 140px;
        height: 140px;
        left: -45px;
        bottom: -50px;
        top: auto;
        right: auto;
        opacity: .26;
        animation-duration: 6.1s;
        animation-delay: .7s;
    }

    @keyframes floatyXL {

        0%,
        100% {
            transform: translateY(0) rotate(-10deg);
        }

        50% {
            transform: translateY(14px) rotate(6deg);
        }
    }

    /* ====== SPARKLES ====== */
    .spark {
        position: absolute;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.85);
        filter: blur(.3px);
        z-index: 2;
        animation: sparkle 2.4s ease-in-out infinite;
        box-shadow: 0 0 18px rgba(255, 255, 255, 0.55);
        opacity: .75;
    }

    .spark--1 {
        left: 18%;
        top: 22%;
        animation-delay: .2s;
    }

    .spark--2 {
        left: 64%;
        top: 18%;
        animation-delay: .8s;
    }

    .spark--3 {
        left: 78%;
        top: 56%;
        animation-delay: 1.4s;
    }

    @keyframes sparkle {

        0%,
        100% {
            transform: scale(.9);
            opacity: .55;
        }

        50% {
            transform: scale(1.45);
            opacity: .95;
        }
    }

    /* ====== Responsive ====== */
    @media (max-width: 576px) {
        .pokemon-jumbo-xl {
            padding: 34px 20px;
            min-height: 320px;
            border-radius: 18px;
        }

        .pokemon-jumbo-xl__subtitle {
            font-size: 15px;
        }

        .pokeball-xl--1 {
            width: 140px;
            height: 140px;
            right: -45px;
            top: -45px;
        }

        .pokeball-xl--2 {
            width: 110px;
            height: 110px;
            left: -40px;
            bottom: -45px;
        }
    }

    /* Respect user motion settings */
    @media (prefers-reduced-motion: reduce) {

        .pokemon-jumbo-xl__glow,
        .pokeball-xl,
        .spark {
            animation: none !important;
        }
    }
</style>

<script>
    
    // buat ngerefresh tiap 30 detik
    setInterval(() => {
        fetch("{{ route('listings.refresh') }}")
            .then(res => res.text())
            .then(html => {
                const container = document.getElementById('latest-listings-container');
                if (container) {
                    container.innerHTML = html;
                }
            })
            .catch(err => console.error('Refresh failed:', err));
    }, 30000); 
</script>
