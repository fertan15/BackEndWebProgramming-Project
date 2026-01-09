@extends('layout.main')

@section('main_contents')
    <section class="section">
        <div class="container-fluid">
            <!-- Breadcrumb -->
            <div class="title-wrapper pt-30 mb-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">My Inventory</li>
                    </ol>
                </nav>
            </div>

            <!-- Header -->
            <div class="row mb-4">
                <div class="col-lg-8">
                    <h2>My Inventory</h2>
                    <p class="text-muted">Manage your collected cards and create listings</p>
                </div>
                <div class="col-lg-4 text-end">
                    <span class="badge bg-success me-2" style="font-size: 14px; padding: 8px 12px;">
                        Tradeable: {{ $tradeableCards->count() }}
                    </span>
                    <span class="badge bg-warning" style="font-size: 14px; padding: 8px 12px;">
                        Locked: {{ $lockedCards->count() }}
                    </span>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Tabs for Tradeable and Locked Cards -->
            <ul class="nav nav-tabs mb-4" id="inventoryTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="tradeable-tab" data-bs-toggle="tab" data-bs-target="#tradeable" 
                            type="button" role="tab" aria-controls="tradeable" aria-selected="true">
                        <i class="lni lni-shopping-cart"></i> Tradeable Cards ({{ $tradeableCards->count() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="locked-tab" data-bs-toggle="tab" data-bs-target="#locked" 
                            type="button" role="tab" aria-controls="locked" aria-selected="false">
                        <i class="lni lni-lock"></i> Locked Cards ({{ $lockedCards->count() }})
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="inventoryTabContent">
                <!-- Tradeable Cards Tab -->
                <div class="tab-pane fade show active" id="tradeable" role="tabpanel" aria-labelledby="tradeable-tab">
                    @if ($tradeableCards->count() > 0)
                        <div class="row g-4">
                            @foreach ($tradeableCards as $item)
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <div class="card inventory-card h-100">
                                        <div class="card-img-top position-relative overflow-hidden" style="height: 280px; background: #f8f9fa;">
                                            <img src="{{ asset($item->card->image_url) }}" 
                                                 alt="{{ $item->card->name }}"
                                                 class="w-100 h-100" 
                                                 style="object-fit: contain; padding: 10px;">
                                            <div class="position-absolute top-2 end-2">
                                                <span class="badge bg-info">{{ $item->card->rarity }}</span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <h6 class="card-title mb-1">{{ $item->card->name }}</h6>
                                            <small class="text-muted d-block mb-2">{{ $item->card->cardSet->name }}</small>
                                            
                                            <div class="mb-2">
                                                <span class="badge" style="
                                                    @if ($item->condition_text == 'Mint') background-color: #28a745;
                                                    @elseif($item->condition_text == 'Near Mint') background-color: #17a2b8;
                                                    @elseif($item->condition_text == 'Lightly Played') background-color: #ffc107; color: #000;
                                                    @else background-color: #6c757d;
                                                    @endif
                                                ">
                                                    {{ $item->condition_text }}
                                                </span>
                                            </div>

                                            <small class="text-muted d-block mb-3">
                                                Added: {{ $item->added_at ? \Carbon\Carbon::parse($item->added_at)->format('M d, Y') : 'N/A' }}
                                            </small>

                                            <div class="d-grid gap-2">
                                                @if($item->is_listed)
                                                    <button type="button" class="btn btn-sm btn-secondary" disabled>
                                                        <i class="lni lni-check"></i> Listed
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" 
                                                            data-bs-target="#listingModal{{ $item->id }}">
                                                        <i class="lni lni-plus"></i> Create Listing
                                                    </button>
                                                @endif
                                                <form action="{{ route('inventory.lock', $item->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-warning w-100" 
                                                            @if($item->is_listed) disabled @endif>
                                                        <i class="lni lni-lock"></i> Lock Card
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Listing Modal for this card -->
                                <div class="modal fade" id="listingModal{{ $item->id }}" tabindex="-1" aria-labelledby="listingModalLabel{{ $item->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="listingModalLabel{{ $item->id }}">
                                                    Create Listing - {{ $item->card->name }}
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('inventory.addListing', $item->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="alert alert-info">
                                                        <small><strong>Card Details:</strong></small><br>
                                                        <small>Condition: <strong>{{ $item->condition_text }}</strong></small><br>
                                                        <small>This listing will be created with the same condition as your collected card.</small>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="price{{ $item->id }}" class="form-label">Listing Price ($) <span class="text-danger">*</span></label>
                                                        <input type="number" class="form-control" id="price{{ $item->id }}" 
                                                               name="price" step="0.01" min="0.01" required
                                                               placeholder="Enter your asking price">
                                                        <small class="text-muted">Set your desired selling price for this card</small>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="lni lni-check"></i> Create Listing
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info" role="alert">
                            <h5>No tradeable cards</h5>
                            <p>All your cards are locked. You can unlock them from the "Locked Cards" tab.</p>
                        </div>
                    @endif
                </div>

                <!-- Locked Cards Tab -->
                <div class="tab-pane fade" id="locked" role="tabpanel" aria-labelledby="locked-tab">
                    @if ($lockedCards->count() > 0)
                        <div class="alert alert-warning mb-4">
                            <i class="lni lni-lock"></i> <strong>Locked Cards</strong> - These cards are protected from being listed. Unlock them to create listings.
                        </div>
                        <div class="row g-4">
                            @foreach ($lockedCards as $item)
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <div class="card inventory-card h-100 border-warning">
                                        <div class="card-img-top position-relative overflow-hidden" style="height: 280px; background: #f8f9fa;">
                                            <img src="{{ asset($item->card->image_url) }}" 
                                                 alt="{{ $item->card->name }}"
                                                 class="w-100 h-100" 
                                                 style="object-fit: contain; padding: 10px; opacity: 0.85;">
                                            <div class="position-absolute top-2 end-2">
                                                <span class="badge bg-warning text-dark">
                                                    <i class="lni lni-lock"></i> Locked
                                                </span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <h6 class="card-title mb-1">{{ $item->card->name }}</h6>
                                            <small class="text-muted d-block mb-2">{{ $item->card->cardSet->name }}</small>
                                            
                                            <div class="mb-2">
                                                <span class="badge" style="
                                                    @if ($item->condition_text == 'Mint') background-color: #28a745;
                                                    @elseif($item->condition_text == 'Near Mint') background-color: #17a2b8;
                                                    @elseif($item->condition_text == 'Lightly Played') background-color: #ffc107; color: #000;
                                                    @else background-color: #6c757d;
                                                    @endif
                                                ">
                                                    {{ $item->condition_text }}
                                                </span>
                                            </div>

                                            <small class="text-muted d-block mb-3">
                                                Added: {{ $item->added_at ? \Carbon\Carbon::parse($item->added_at)->format('M d, Y') : 'N/A' }}
                                            </small>

                                            <div class="d-grid gap-2">
                                                <form action="{{ route('inventory.unlock', $item->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success w-100">
                                                        <i class="lni lni-unlock"></i> Unlock Card
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info" role="alert">
                            <h5>No locked cards</h5>
                            <p>You don't have any locked cards. Lock cards from the "Tradeable Cards" tab to protect them.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <style>
        .inventory-card {
            transition: transform 0.2s, box-shadow 0.2s;
            border: 1px solid #e9ecef;
        }
        .inventory-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }
        .card-img-top {
            border-bottom: 1px solid #e9ecef;
        }
    </style>
@endsection
