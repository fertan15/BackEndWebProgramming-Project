@extends('layout.main') {{-- Assuming you have a master layout --}}

@section('main_contents')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h4 class="mb-0 fw-bold"><i class="lni lni-orders me-2"></i>My Order History</h4>
                </div>
                <div class="card-body">
                    @if($orders->isEmpty())
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="lni lni-shopping-basket text-muted" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="text-muted">You haven't purchased any cards yet.</h5>
                            <a href="{{ route('home') }}" class="btn btn-primary mt-3">Start Browsing</a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">Card Details</th>
                                        <th scope="col">Seller</th>
                                        <th scope="col">Price Paid</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    {{-- Image Handling --}}
                                                    <div style="width: 50px; height: 70px; overflow: hidden; border-radius: 4px; margin-right: 15px; background: #f0f0f0;">
                                                        @if($item->listing && $item->listing->card)
                                                            <img src="{{ asset($item->listing->card->image_url) }}" 
                                                                 alt="{{ $item->listing->card->name }}" 
                                                                 style="width: 100%; height: 100%; object-fit: cover;">
                                                        @else
                                                            <div class="d-flex align-items-center justify-content-center h-100 text-muted">?</div>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-bold">
                                                            {{ $item->listing && $item->listing->card ? $item->listing->card->name : 'Unknown Card' }}
                                                        </h6>
                                                        <small class="text-muted">
                                                            Condition: {{ $item->listing ? $item->listing->condition_text : 'N/A' }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($item->listing && $item->listing->seller)
                                                    <a href="#" class="text-decoration-none text-dark fw-500">
                                                        {{ $item->listing->seller->name }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">User Deleted</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="fw-bold text-success">
                                                    ${{ number_format($item->price_at_purchase, 2) }}
                                                </span>
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($item->purchased_at)->format('M d, Y') }}
                                                <br>
                                                <small class="text-muted">{{ \Carbon\Carbon::parse($item->purchased_at)->format('H:i A') }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                                                    Completed
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection