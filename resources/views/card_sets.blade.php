@extends('layout.main')

@section('main_contents')
    <section class="section">
        <div class="container-fluid">
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
                                <a href="{{ route('set.cards', $set->id) }}" class="btn btn-primary mt-auto">
                                    View Cards
                                </a>
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