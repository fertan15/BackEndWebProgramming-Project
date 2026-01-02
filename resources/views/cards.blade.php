@extends('layout.main')

@section('main_contents')
    <section class="section">
        <div class="container-fluid">
            <div class="title-wrapper pt-30">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="title">
                            <strong>
                                <h2>Pokemon Card</h2>
                            </strong>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="breadcrumb-wrapper">
                            <strong>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item">
                                            <a href="{{ route('card_sets') }}">Cards Sets</a>
                                        </li>
                                        <li class="breadcrumb-item active" aria-current="page">
                                            Cards
                                        </li>
                                    </ol>
                                </nav>
                            </strong>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-4">
                @foreach ($cards as $item)
                    <div class="col-xl-3 col-lg-3 col-sm-6">
                        <div class="card">
                            <img src="{{ asset('images/cards/' . $item->image_url) }}" class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">{{ $item->name }}</h5>
                                <a href="#" class="btn btn-primary">Trade</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <br>
        </div>
    </section>
@endsection
