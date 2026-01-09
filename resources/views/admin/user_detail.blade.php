@extends('layout.main')

@section('main_contents')
    <section class="section">
        <div class="container-fluid">
            <!-- Header -->
            <div class="title-wrapper pt-30">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="title">
                            <h2>User Details</h2>
                            <p class="text-muted">Admin view - Read-only user information</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="breadcrumb-wrapper">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('admin.users') }}">Users</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        User Detail
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Back Button -->
            <div class="mb-3">
                <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="lni lni-arrow-left"></i> Back to Users
                </a>
            </div>

            <div class="row">
                <!-- Profile Picture & Status Card -->
                <div class="col-lg-4 mb-30">
                    <!-- Profile Picture -->
                    <div class="card-style">
                        <div class="title mb-20">
                            <h6>Profile Picture</h6>
                        </div>
                        <div class="text-center">
                            <div class="profile-picture-preview mb-20" style="height: 280px; display: flex; align-items: center; justify-content: center; border-radius: 12px; background: #f5f5f5; overflow: hidden;">
                                <div id="profilePreview" class="avatar-large" data-name="{{ $user->name ?? 'User' }}" data-image="{{ $user->identity_image_url ?? '' }}" style="width: 220px; height: 220px;"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Account Status Information -->
                    <div class="card-style mt-30">
                        <div class="title mb-20">
                            <h6>Account Status</h6>
                        </div>
                        <div class="account-info">
                            <div class="info-item mb-15">
                                <label class="text-muted text-sm d-block">Status</label>
                                <p class="text-dark">
                                    @php
                                        $accountClass = match($user->account_status) {
                                            'active' => 'success',
                                            'verify', 'pending' => 'warning',
                                            'banned' => 'danger',
                                            default => 'secondary',
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $accountClass }} text-uppercase">{{ $user->account_status }}</span>
                                </p>
                            </div>
                            <div class="info-item mb-15">
                                <label class="text-muted text-sm d-block">Identity Status</label>
                                <p class="text-dark">
                                    @php
                                        $identityClass = match($user->identity_status) {
                                            'approved', 'verified' => 'success',
                                            'pending' => 'warning',
                                            'rejected' => 'danger',
                                            default => 'secondary',
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $identityClass }} text-uppercase">{{ $user->identity_status }}</span>
                                </p>
                            </div>
                            <div class="info-item mb-15">
                                <label class="text-muted text-sm d-block">Admin Status</label>
                                <p class="text-dark">
                                    @if($user->is_admin)
                                        <span class="badge bg-primary">Administrator</span>
                                    @else
                                        <span class="badge bg-light text-dark">Regular User</span>
                                    @endif
                                </p>
                            </div>
                            <div class="info-item">
                                <label class="text-muted text-sm d-block">Balance</label>
                                <p class="text-dark font-weight-bold">Rp {{ number_format($user->balance ?? 0, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Registration Info -->
                    <div class="card-style mt-30">
                        <div class="title mb-20">
                            <h6>Registration Information</h6>
                        </div>
                        <div class="info-item mb-15">
                            <label class="text-muted text-sm d-block">Joined Date</label>
                            <p class="text-dark">{{ $user->created_at ? $user->created_at->format('F d, Y H:i') : 'N/A' }}</p>
                        </div>
                        <div class="info-item">
                            <label class="text-muted text-sm d-block">Last Updated</label>
                            <p class="text-dark">{{ $user->updated_at ? $user->updated_at->format('F d, Y H:i') : 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- User Information (Read-only) -->
                <div class="col-lg-8">
                    <!-- Personal Information -->
                    <div class="card-style">
                        <div class="title mb-20">
                            <h6>Personal Information</h6>
                        </div>

                        <div class="row mb-20">
                            <div class="col-12">
                                <div class="input-style-1">
                                    <label>Full Name</label>
                                    <input type="text" value="{{ $user->name ?? 'N/A' }}" disabled style="background-color: #f5f5f5; cursor: not-allowed;">
                                </div>
                            </div>
                        </div>

                        <div class="row mb-20">
                            <div class="col-12">
                                <div class="input-style-1">
                                    <label>Username</label>
                                    <input type="text" value="{{ $user->username ?? 'N/A' }}" disabled style="background-color: #f5f5f5; cursor: not-allowed;">
                                </div>
                            </div>
                        </div>

                        <div class="row mb-20">
                            <div class="col-12">
                                <div class="input-style-1">
                                    <label>Email Address</label>
                                    <input type="email" value="{{ $user->email ?? 'N/A' }}" disabled style="background-color: #f5f5f5; cursor: not-allowed;">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="input-style-1">
                                    <label>Phone Number</label>
                                    <input type="tel" value="{{ $user->phone_number ?? 'N/A' }}" disabled style="background-color: #f5f5f5; cursor: not-allowed;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Identity Verification Details -->
                    <div class="card-style mt-30">
                        <div class="title mb-20 d-flex justify-content-between align-items-center">
                            <h6>Identity Verification</h6>
                            @php
                                $identityBadgeClass = match($user->identity_status) {
                                    'verified' => 'success',
                                    'rejected' => 'danger',
                                    'pending' => 'warning',
                                    default => 'secondary',
                                };
                            @endphp
                            <span class="badge bg-{{ $identityBadgeClass }}">{{ ucfirst($user->identity_status) }}</span>
                        </div>

                        <div class="row mb-20">
                            <div class="col-md-6">
                                <div class="input-style-1">
                                    <label>ID Type</label>
                                    <input type="text" value="{{ $user->identity_type ?? 'N/A' }}" disabled style="background-color: #f5f5f5; cursor: not-allowed;">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-style-1">
                                    <label>ID Number</label>
                                    <input type="text" value="{{ $user->identity_number ?? 'N/A' }}" disabled style="background-color: #f5f5f5; cursor: not-allowed;">
                                </div>
                            </div>
                        </div>

                        <!-- Identity Card Document -->
                        <div class="mb-20">
                            <label class="text-muted text-sm d-block">Identity Document</label>
                            <div style="border: 1px solid #e0e0e0; border-radius: 8px; padding: 20px; background-color: #f9f9f9;">
                                @if(!empty($user->identity_card_url))
                                    <img src="{{ asset($user->identity_card_url) }}" alt="Identity Document" class="img-fluid rounded" style="max-height: 400px; object-fit: contain; width: 100%;">
                                    <div class="mt-2">
                                        <a href="{{ asset($user->identity_card_url) }}" target="_blank" class="btn btn-sm btn-outline-primary">View Full Image</a>
                                    </div>
                                @else
                                    <p class="text-muted text-center mb-0">No identity document uploaded.</p>
                                @endif
                            </div>
                        </div>

                        <!-- Profile Picture URL -->
                        <div class="row">
                            <div class="col-12">
                                <div class="input-style-1">
                                    <label>Profile Picture URL</label>
                                    <textarea disabled style="background-color: #f5f5f5; cursor: not-allowed; min-height: 60px;">{{ $user->identity_image_url ?? 'N/A' }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Admin Actions -->
                    <div class="card-style mt-30">
                        <div class="title mb-20">
                            <h6>Admin Actions</h6>
                        </div>
                        <form action="{{ route('admin.users.ban', $user->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('{{ $user->account_status === 'banned' ? 'Are you sure you want to unban this user?' : 'Are you sure you want to ban this user?' }}');">
                            @csrf
                            @method('PUT')
                            @if($user->account_status === 'banned')
                                <button type="submit" class="btn btn-outline-success">
                                    <i class="lni lni-check-circle"></i> Unban User
                                </button>
                            @else
                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="lni lni-close-circle"></i> Ban User
                                </button>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const avatarEl = document.getElementById('profilePreview');
            
            const getInitials = (name) => name
                .split(' ')
                .filter(Boolean)
                .map(part => part[0].toUpperCase())
                .slice(0, 2)
                .join('') || 'U';

            const getColorFromName = (name) => {
                const colors = ['#5A67D8', '#2B6CB0', '#2C5282', '#805AD5', '#B83280', '#DD6B20', '#3182CE'];
                const code = name.split('').reduce((sum, char) => sum + char.charCodeAt(0), 0);
                return colors[code % colors.length];
            };

            const renderAvatar = (el, url, initials, color) => {
                el.innerHTML = '';
                el.style.display = 'flex';
                el.style.alignItems = 'center';
                el.style.justifyContent = 'center';
                el.style.borderRadius = '50%';
                el.style.backgroundColor = '#f5f5f5';
                el.style.overflow = 'hidden';

                const showInitials = () => {
                    const circle = document.createElement('div');
                    circle.textContent = initials;
                    circle.style.width = '100%';
                    circle.style.height = '100%';
                    circle.style.display = 'flex';
                    circle.style.alignItems = 'center';
                    circle.style.justifyContent = 'center';
                    circle.style.fontSize = '48px';
                    circle.style.fontWeight = '700';
                    circle.style.color = '#fff';
                    circle.style.backgroundColor = color;
                    el.appendChild(circle);
                };

                if (url) {
                    const img = document.createElement('img');
                    img.src = url;
                    img.alt = 'Profile picture';
                    img.style.width = '100%';
                    img.style.height = '100%';
                    img.style.objectFit = 'cover';
                    img.onerror = showInitials;
                    img.onload = () => {
                        el.style.backgroundColor = '#fff';
                        el.appendChild(img);
                    };
                    return;
                }

                showInitials();
            };

            const initials = getInitials(avatarEl.dataset.name || 'User');
            const color = getColorFromName(avatarEl.dataset.name || 'User');
            renderAvatar(avatarEl, avatarEl.dataset.image, initials, color);
        });
    </script>
@endsection
