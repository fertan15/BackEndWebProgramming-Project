@extends('layout.main')
@section('main_contents')
    <section class="section">
        <div class="container-fluid">
            <!-- ========== title-wrapper start ========== -->
            <div class="title-wrapper pt-30">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="title">
                            <h2>My Profile</h2>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="breadcrumb-wrapper">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('home') }}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        My Profile
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ========== title-wrapper end ========== -->

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> Please fix the errors below.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <!-- Profile Picture Preview Card -->
                <div class="col-lg-4 mb-30">
                    <div class="card-style">
                        <div class="title mb-30 d-flex justify-content-between align-items-center">
                            <h6>Profile Picture</h6>
                            <span class="text-muted text-xs">Auto fallback to initials</span>
                        </div>
                        <div class="text-center">
                            <div class="profile-picture-preview mb-20" style="height: 280px; display: flex; align-items: center; justify-content: center; border-radius: 12px; background: #f5f5f5; overflow: hidden;">
                                <div id="profilePreview" class="avatar-large" data-name="{{ $user->name ?? 'User' }}" data-image="{{ $user->identity_image_url ?? '' }}" style="width: 220px; height: 220px;"></div>
                            </div>
                            <p class="text-muted text-sm">Preview uses your uploaded image or falls back to your initials</p>
                        </div>
                    </div>

                    <!-- Account Status Card -->
                    <div class="card-style mt-30">
                        <div class="title mb-20">
                            <h6>Account Information</h6>
                        </div>
                        <div class="account-info">
                            <div class="info-item mb-15">
                                <label class="text-muted text-sm">Account Status</label>
                                <p class="text-dark">
                                    <span class="badge" style="background-color: 
                                        @if($user && $user->account_status === 'active') #4CAF50 @elseif($user && $user->account_status === 'suspended') #FF9800 @else #f44336 @endif
                                    ">
                                        {{ $user ? ucfirst($user->account_status) : 'N/A' }}
                                    </span>
                                </p>
                            </div>
                            <div class="info-item mb-15">
                                <label class="text-muted text-sm">Identity Status</label>
                                <p class="text-dark">
                                    <span class="badge" style="background-color: 
                                        @if($user && $user->identity_status === 'verified') #4CAF50 @elseif($user && $user->identity_status === 'rejected') #f44336 @else #2196F3 @endif
                                    ">
                                        {{ $user ? ucfirst($user->identity_status) : 'Unverified' }}
                                    </span>
                                </p>
                            </div>
                            <div class="info-item">
                                <label class="text-muted text-sm">Balance</label>
                                <p class="text-dark font-weight-bold">Rp {{ number_format($user ? $user->balance : 0, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Identity Document (read-only) -->
                    <div class="card-style mt-30">
                        <div class="title mb-20 d-flex justify-content-between align-items-center">
                            <h6>Identity Verification</h6>
                            <span class="badge" style="background-color: 
                                @if($user && $user->identity_status === 'verified') #4CAF50 @elseif($user && $user->identity_status === 'rejected') #f44336 @else #2196F3 @endif">
                                {{ $user ? ucfirst($user->identity_status) : 'Unverified' }}
                            </span>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted text-sm d-block">ID Type</label>
                            <strong>{{ $user->identity_type ?? '—' }}</strong>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted text-sm d-block">ID Number</label>
                            <strong>{{ $user->identity_number ?? '—' }}</strong>
                        </div>
                        <div class="text-center">
                            @if(!empty($user->identity_card_url))
                                <img src="{{ asset($user->identity_card_url) }}" alt="Identity Document" class="img-fluid rounded mb-2" style="max-height: 260px; object-fit: contain;">
                                <div>
                                    <a href="{{ asset($user->identity_card_url) }}" target="_blank">Open full image</a>
                                </div>
                            @else
                                <p class="text-muted mb-0">No identity document uploaded.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Editable Profile Form -->
                <div class="col-lg-8">
                    <div class="card-style">
                        <div class="title mb-20 d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div>
                                <h6>Edit Profile Information</h6>
                                <p class="text-muted text-sm mb-0">Click "Edit" to enable fields. Email cannot be changed.</p>
                            </div>
                            <button type="button" id="editToggle" class="main-btn primary-btn btn-hover" style="min-width: 120px;">Edit</button>
                        </div>

                        @if($user && $user->identity_status !== 'verified')
                            <div class="alert alert-warning d-flex justify-content-between align-items-center" role="alert">
                                <span>Your identity is not verified yet.</span>
                                <a class="main-btn primary-btn btn-sm" href="{{ route('verify.identity') }}">Verify Now</a>
                            </div>
                        @endif

                        <form action="{{ route('update_profile') }}" method="POST">
                            @csrf

                            <!-- Name -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="input-style-1">
                                        <label>Full Name</label>
                                             <input class="editable-field" type="text" name="name" placeholder="Enter full name" 
                                                 value="{{ old('name', $user ? $user->name : '') }}" required readonly>
                                        @error('name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Username -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="input-style-1">
                                        <label>Username</label>
                                             <input class="editable-field" type="text" name="username" placeholder="Enter username" 
                                                 value="{{ old('username', $user ? $user->username : '') }}" readonly>
                                        @error('username')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Email (Readonly) -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="input-style-1">
                                        <label>Email (Cannot be changed)</label>
                                        <input type="email" placeholder="Email" 
                                               value="{{ $user ? $user->email : '' }}" disabled style="background-color: #f5f5f5; cursor: not-allowed;">
                                    </div>
                                </div>
                            </div>

                            <!-- Phone Number -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="input-style-1">
                                        <label>Phone Number</label>
                                             <input class="editable-field" type="tel" name="phone_number" placeholder="Enter phone number (e.g., +62123456789)" 
                                                 value="{{ old('phone_number', $user ? $user->phone_number : '') }}" readonly>
                                        @error('phone_number')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Identity Image URL with Real-time Preview -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="input-style-1">
                                        <label>Profile Picture URL</label>
                                             <input class="editable-field" type="url" name="identity_image_url" id="identityImageUrl" 
                                                 placeholder="Enter image URL (https://...)" 
                                                 value="{{ old('identity_image_url', $user ? $user->identity_image_url : '') }}" readonly>
                                        <small class="text-muted">The preview above will update as you type</small>
                                        @error('identity_image_url')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Submit Button -->
                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" id="saveBtn" class="main-btn primary-btn btn-hover" disabled>
                                        <i class="lni lni-save"></i> Save Changes
                                    </button>
                                    <a href="{{ route('home') }}" class="main-btn secondary-btn btn-hover" style="margin-left: 10px;">
                                        <i class="lni lni-arrow-left"></i> Cancel
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const avatarEl = document.getElementById('profilePreview');
            const imageUrlInput = document.getElementById('identityImageUrl');
            const editBtn = document.getElementById('editToggle');
            const saveBtn = document.getElementById('saveBtn');
            const editableFields = document.querySelectorAll('.editable-field');
            const editableSelects = document.querySelectorAll('.editable-select');

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
            const setPreview = (url) => renderAvatar(avatarEl, url, initials, color);

            setPreview(avatarEl.dataset.image);

            imageUrlInput.addEventListener('input', function () {
                setPreview(this.value.trim());
            });

            let editing = false;
            const setEditing = (state) => {
                editing = state;
                editableFields.forEach((field) => {
                    field.readOnly = !state;
                    field.classList.toggle('light-bg', !state);
                });
                editableSelects.forEach((select) => {
                    select.disabled = !state;
                    select.classList.toggle('light-bg', !state);
                });
                saveBtn.disabled = !state;
                editBtn.textContent = state ? 'Stop Editing' : 'Edit';
            };

            editBtn.addEventListener('click', () => setEditing(!editing));
        });
    </script>
@endsection
