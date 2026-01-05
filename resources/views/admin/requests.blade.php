@extends('layout.main')

@section('main_contents')
<section class="section">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h2 class="mb-0">Verification Requests</h2>
                <small class="text-muted">Showing accounts pending identity/verification</small>
            </div>
            <div>
                <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">Back to Users</a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @forelse($pendingUsers as $user)
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-4 col-lg-3">
                            <div class="border rounded p-2 text-center bg-light">
                                @if(!empty($user->identity_image_url))
                                    <img src="{{ asset($user->identity_image_url) }}" alt="Identity" class="img-fluid" style="max-height: 260px; object-fit: contain;">
                                @else
                                    <div class="text-muted py-5">No identity image</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-5 col-lg-6">
                            <div class="mb-2"><strong>Name:</strong> {{ $user->name }}</div>
                            <div class="mb-2"><strong>Username:</strong> {{ $user->username }}</div>
                            <div class="mb-2"><strong>Email:</strong> {{ $user->email }}</div>
                            <div class="mb-2"><strong>Phone:</strong> {{ $user->phone_number }}</div>
                            <div class="mb-2"><strong>ID Type:</strong> {{ $user->identity_type ?? '—' }}</div>
                            <div class="mb-2"><strong>ID Number:</strong> {{ $user->identity_number ?? '—' }}</div>
                            <div class="mb-2"><strong>Account:</strong>
                                @php
                                    $account = $user->account_status ?? 'unknown';
                                    $accountClass = match($account) {
                                        'active' => 'success',
                                        'verify', 'pending' => 'warning',
                                        'banned' => 'danger',
                                        default => 'secondary',
                                    };
                                @endphp
                                <span class="badge bg-{{ $accountClass }} text-uppercase">{{ $account }}</span>
                            </div>
                            <div class="mb-2"><strong>Identity:</strong>
                                @php
                                    $identity = $user->identity_status ?? 'unknown';
                                    $identityClass = match($identity) {
                                        'approved', 'verified' => 'success',
                                        'pending' => 'warning',
                                        'rejected' => 'danger',
                                        default => 'secondary',
                                    };
                                @endphp
                                <span class="badge bg-{{ $identityClass }} text-uppercase">{{ $identity }}</span>
                            </div>
                            <div class="text-muted"><small>Joined {{ optional($user->created_at)->format('M d, Y') }}</small></div>
                        </div>
                        <div class="col-md-3 col-lg-3 d-flex gap-2 justify-content-md-end align-items-start">
                            <form action="{{ route('admin.requests.approve', $user->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success w-100">Approve</button>
                            </form>
                            <form action="{{ route('admin.requests.reject', $user->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger w-100">Reject</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-secondary">No pending verification requests.</div>
        @endforelse
    </div>
</section>
@endsection
