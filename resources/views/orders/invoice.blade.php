@extends('layout.main')

@section('main_contents')
<section class="section">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0">Invoice</h2>
                <small class="text-muted">Order #{{ $order->id }}</small>
            </div>
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">Back</a>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="text-muted">Buyer</h6>
                        <p class="mb-0">{{ $order->buyer->name ?? 'N/A' }}</p>
                        <small class="text-muted">{{ $order->buyer->email ?? '' }}</small>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <h6 class="text-muted">Seller</h6>
                        <p class="mb-0">{{ $order->listing->seller->name ?? 'N/A' }}</p>
                        <small class="text-muted">{{ $order->listing->seller->email ?? '' }}</small>
                    </div>
                </div>

                <div class="table-responsive mb-3">
                    <table class="table">
                        <thead class="table-light">
                            <tr>
                                <th>Item</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <strong>{{ $order->listing->card->name }}</strong><br>
                                    <small class="text-muted">Condition: {{ $order->listing->condition_text }}</small>
                                </td>
                                <td>${{ number_format($order->price_at_purchase, 2) }}</td>
                                <td>{{ $order->quantity }}</td>
                                <td>${{ number_format($total, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Purchased At</h6>
                        <p class="mb-0">{{ is_string($order->purchased_at) ? \Carbon\Carbon::parse($order->purchased_at)->format('M d, Y H:i') : $order->purchased_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div class="text-end">
                        <h5 class="mb-1">Grand Total</h5>
                        <h3 class="text-primary mb-0">${{ number_format($total, 2) }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
