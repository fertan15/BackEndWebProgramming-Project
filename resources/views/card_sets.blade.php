@extends('layout.main')

@section('main_contents')
    <section class="section">
        <div class="container-fluid">
            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>⚠️ Warning:</strong> {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>❌ Error:</strong> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>✅ Success:</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="title-wrapper pt-30">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="title">
                            <strong>
                                <h2>Pokemon Card Sets</h2>
                            </strong>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="breadcrumb-wrapper">
                            <strong>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item">
                                            <a href="{{ route('home') }}">Home</a>
                                        </li>
                                        <li class="breadcrumb-item active" aria-current="page">
                                            Card Sets
                                        </li>
                                    </ol>
                                </nav>
                            </strong>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-4">
                @forelse ($card_set as $set)
                    <div class="col-xl-3 col-lg-4 col-sm-6">
                        <div class="card h-100">
                            <img src="{{ asset($set->image_url) }}" class="card-img-top" alt="{{ $set->name }}">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $set->name }}</h5>
                                <p class="card-text text-muted small">
                                    {{ $set->cards_count }} cards • Released {{ $set->release_date->format('M Y') }}
                                </p>
                                @if($set->description)
                                    <p class="card-text small mb-3">{{ Str::limit($set->description, 100) }}</p>
                                @endif
                                <div class="d-flex gap-2 mt-auto">
                                    <a href="{{ route('set.cards', $set->id) }}" class="btn btn-primary flex-grow-1">
                                        View Cards
                                    </a>
                                    @if(Auth::check() && Auth::user()->is_admin)
                                        <form action="{{ route('admin.card_sets.delete', $set->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this card set?@if($set->cards_count > 0)\n\n⚠️ This set has {{ $set->cards_count }} card(s) that will also be deleted.@endif');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" title="Delete Set">
                                                <i class="lni lni-trash-can"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info">
                            No card sets available at the moment.
                        </div>
                    </div>
                @endforelse
            </div>
            <br>
        </div>
    </section>
@endsection