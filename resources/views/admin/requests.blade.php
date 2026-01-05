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
            <div class="verify-card mb-3">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between align-items-start mb-3 gap-2">
                        <div>
                            <h5 class="mb-1">{{ $user->name }}</h5>
                            <div class="text-muted small">Joined {{ optional($user->created_at)->format('M d, Y') }}</div>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            @php
                                $account = $user->account_status ?? 'unknown';
                                $accountClass = match($account) {
                                    'active' => 'success',
                                    'verify', 'pending' => 'warning',
                                    'banned' => 'danger',
                                    default => 'secondary',
                                };
                                $identity = $user->identity_status ?? 'unknown';
                                $identityClass = match($identity) {
                                    'approved', 'verified' => 'success',
                                    'pending' => 'warning',
                                    'rejected' => 'danger',
                                    default => 'secondary',
                                };
                            @endphp
                            <span class="badge bg-{{ $accountClass }}">Account: {{ $account }}</span>
                            <span class="badge bg-{{ $identityClass }}">Identity: {{ $identity }}</span>
                        </div>
                    </div>

                    <div class="row g-3 align-items-start">
                        <div class="col-md-4 col-lg-3">
                            <div class="verify-image text-center">
                                @if(!empty($user->identity_image_url))
                                    <img src="{{ asset($user->identity_image_url) }}" alt="Identity" class="img-fluid verify-image__img">
                                    <div class="small mt-2">
                                        <a href="{{ asset($user->identity_image_url) }}" target="_blank">Open full image</a>
                                    </div>
                                @else
                                    <div class="text-muted py-5">No identity image</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-5 col-lg-6">
                            <div class="verify-grid">
                                <div><span class="label">Name</span><span>{{ $user->name }}</span></div>
                                <div><span class="label">Username</span><span>{{ $user->username }}</span></div>
                                <div><span class="label">Email</span><span>{{ $user->email }}</span></div>
                                <div><span class="label">Phone</span><span>{{ $user->phone_number }}</span></div>
                                <div><span class="label">ID Type</span><span>{{ $user->identity_type ?? '—' }}</span></div>
                                <div><span class="label">ID Number</span><span>{{ $user->identity_number ?? '—' }}</span></div>
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3 d-flex flex-column gap-2 align-items-stretch">
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

<style>
section.section {
    background: #f6f8fb;
    padding-top: 20px;
    padding-bottom: 20px;
}

.verify-card {
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.05);
    background: #ffffff;
}

.verify-card .card-body {
    padding: 20px 24px;
}

.verify-image {
    border: 1px dashed #cbd5e1;
    border-radius: 10px;
    background: #f8fafc;
    padding: 12px;
}

.verify-image__img {
    max-height: 260px;
    object-fit: contain;
}

.verify-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 10px 14px;
}

.verify-grid .label {
    display: block;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: #6b7280;
}

.verify-grid div {
    padding: 10px 12px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    background: #fff;
}

@media (max-width: 767px) {
    .verify-grid {
        grid-template-columns: 1fr;
    }
}
</style>
