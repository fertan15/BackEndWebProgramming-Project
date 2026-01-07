@extends('layout.main')

@section('main_contents')
<section class="section">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h2 class="mb-0">Create Card</h2>
                <small class="text-muted">Admin • upload new card to a set</small>
            </div>
            <div>
                <a href="{{ route('admin.card_sets.create') }}" class="btn btn-outline-info me-2">Create Card Set</a>
                <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">Back to Users</a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.cards.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                    @csrf
                    <div class="col-md-6">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Card Set</label>
                        <select name="card_set_id" class="form-select" required>
                            <option value="" disabled selected>Select set</option>
                            @foreach($cardSets as $set)
                                <option value="{{ $set->id }}" {{ old('card_set_id') == $set->id ? 'selected' : '' }}>
                                    {{ $set->name }} @if(!empty($set->release_date)) ({{ $set->release_date }}) @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Card Type</label>
                        <input type="text" name="card_type" class="form-control" value="{{ old('card_type') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Rarity</label>
                        <input type="text" name="rarity" class="form-control" value="{{ old('rarity') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Edition</label>
                        <input type="text" name="edition" class="form-control" value="{{ old('edition') }}" placeholder="e.g. 1st Edition">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Estimated Market Price</label>
                        <input type="number" step="0.01" min="0" name="estimated_market_price" class="form-control" value="{{ old('estimated_market_price') }}" placeholder="0.00">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Card Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*" required>
                        <small class="text-muted">Max 2MB. Stored in public/images/cards.</small>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Create Card</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
