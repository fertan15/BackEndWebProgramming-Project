@extends('layout.main')

@section('main_contents')
    <section class="section">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-0">Transaction History</h2>
                    <small class="text-muted">View your buying and selling history</small>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card mb-4">
                <div class="card-header bg-white border-bottom">
                    <ul class="nav nav-tabs card-header-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="buying-tab" data-bs-toggle="tab" data-bs-target="#buying" type="button" role="tab" aria-controls="buying" aria-selected="true">
                                🛒 Purchases
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="selling-tab" data-bs-toggle="tab" data-bs-target="#selling" type="button" role="tab" aria-controls="selling" aria-selected="false">
                                💰 Sales
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="tab-content card-body">
                    <!-- Buying History Tab -->
                    <div class="tab-pane fade show active" id="buying" role="tabpanel" aria-labelledby="buying-tab">
                        @if($buyingHistory->count())
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Card</th>
                                            <th>Seller</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Total</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($buyingHistory as $purchase)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('card.detail', $purchase->listing->card->id) }}" class="text-decoration-none">
                                                        <strong>{{ $purchase->listing->card->name }}</strong>
                                                    </a>
                                                    <br>
                                                    <small class="text-muted">{{ $purchase->listing->condition_text }}</small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info">{{ $purchase->listing->seller->name }}</span>
                                                </td>
                                                <td>
                                                    <strong class="text-primary">${{ number_format($purchase->price_at_purchase, 2) }}</strong>
                                                </td>
                                                <td>{{ $purchase->quantity }}</td>
                                                <td>
                                                    <strong>${{ number_format($purchase->price_at_purchase * $purchase->quantity, 2) }}</strong>
                                                </td>
                                                <td>{{ is_string($purchase->purchased_at) ? \Carbon\Carbon::parse($purchase->purchased_at)->format('M d, Y H:i') : $purchase->purchased_at->format('M d, Y H:i') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <p class="text-muted mb-3">No purchases yet.</p>
                                <a href="{{ route('cards') }}" class="btn btn-primary">Browse Cards</a>
                            </div>
                        @endif
                    </div>

                    <!-- Selling History Tab -->
                    <div class="tab-pane fade" id="selling" role="tabpanel" aria-labelledby="selling-tab">
                        @if($sellingHistory->count())
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Card</th>
                                            <th>Buyer</th>
                                            <th>Price per Unit</th>
                                            <th>Quantity Sold</th>
                                            <th>Total Revenue</th>
                                            <th>Date Sold</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($sellingHistory as $sale)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('card.detail', $sale->listing->card->id) }}" class="text-decoration-none">
                                                        <strong>{{ $sale->listing->card->name }}</strong>
                                                    </a>
                                                    <br>
                                                    <small class="text-muted">{{ $sale->listing->condition_text }}</small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-success">{{ $sale->buyer->name }}</span>
                                                </td>
                                                <td>
                                                    <strong class="text-success">${{ number_format($sale->price_at_purchase, 2) }}</strong>
                                                </td>
                                                <td>{{ $sale->quantity }}</td>
                                                <td>
                                                    <strong class="text-success">${{ number_format($sale->price_at_purchase * $sale->quantity, 2) }}</strong>
                                                </td>
                                                <td>{{ is_string($sale->purchased_at) ? \Carbon\Carbon::parse($sale->purchased_at)->format('M d, Y H:i') : $sale->purchased_at->format('M d, Y H:i') }}</td>
                                                <td>
                                                    <span class="badge bg-success">Completed</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="alert alert-info mt-4">
                                <strong>Total Revenue:</strong> ${{ number_format($sellingHistory->sum(function($sale) { return $sale->price_at_purchase * $sale->quantity; }), 2) }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <p class="text-muted mb-3">No sales yet.</p>
                                <a href="{{ route('inventory.index') }}" class="btn btn-primary">Create Listing</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

<style>
    .nav-tabs .nav-link {
        color: #6c757d;
        border: none;
        border-bottom: 2px solid transparent;
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
    }

    .nav-tabs .nav-link:hover {
        color: #365AF7;
        border-bottom-color: #365AF7;
    }

    .nav-tabs .nav-link.active {
        color: #365AF7;
        border-bottom-color: #365AF7;
        background: none;
    }
</style>
