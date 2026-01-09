@extends('layout.main')

@section('main_contents')
<section class="section">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h2 class="mb-0">Users</h2>
                <small class="text-muted">Admin view • account + verification status</small>
            </div>
            <div>
                <a href="{{ route('admin.cards.create') }}" class="btn btn-primary">Create Card</a>
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

        <div class="card">
            <div class="card-body table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Account</th>
                            <th>Identity</th>
                            <th>Admin</th>
                            <th>Joined</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone_number }}</td>
                                <td>
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
                                </td>
                                <td>
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
                                </td>
                                <td>
                                    @if($user->is_admin)
                                        <span class="badge bg-primary">Admin</span>
                                    @else
                                        <span class="badge bg-light text-dark">User</span>
                                    @endif
                                </td>
                                <td>{{ optional($user->created_at)->format('M d, Y') }}</td>
                                <td class="d-flex gap-2">
                                    <a href="{{ route('view_profile') }}?user_id={{ $user->id }}" class="btn btn-outline-primary btn-sm" role="button">Detail</a>
                                    <form action="{{ route('admin.users.ban', $user->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Ban this user?');">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">Ban</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-3">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
