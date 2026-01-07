@extends('layout.main')

@section('main_contents')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-lg-8 mx-auto">
                <div class="card" style="border-radius: 15px; border: none; box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
                    <div class="card-body" style="padding: 30px;">
                        
                        <!-- Header -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h3 class="mb-1" style="font-weight: 700; color: #1a1a1a;">Create Notification</h3>
                                <p class="text-muted mb-0">Send a notification to users</p>
                            </div>
                            <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-outline-secondary" style="border-radius: 8px; padding: 8px 20px;">
                                <i class="lni lni-arrow-left"></i> Back
                            </a>
                        </div>

                        <!-- Error Messages -->
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 10px; border-left: 4px solid #ef4444;">
                                <strong>Error!</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- Create Form -->
                        <form action="{{ route('notifications.store') }}" method="POST">
                            @csrf

                            <!-- Title -->
                            <div class="mb-4">
                                <label for="title" class="form-label" style="font-weight: 600; color: #374151;">Title</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title') }}" 
                                       placeholder="Enter notification title" 
                                       style="border-radius: 8px; padding: 12px;" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Message -->
                            <div class="mb-4">
                                <label for="message" class="form-label" style="font-weight: 600; color: #374151;">Message</label>
                                <textarea class="form-control @error('message') is-invalid @enderror" 
                                          id="message" name="message" rows="4" 
                                          placeholder="Enter notification message" 
                                          style="border-radius: 8px; padding: 12px;" required>{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Type -->
                            <div class="mb-4">
                                <label for="type" class="form-label" style="font-weight: 600; color: #374151;">Type</label>
                                <select class="form-select @error('type') is-invalid @enderror" 
                                        id="type" name="type" 
                                        style="border-radius: 8px; padding: 12px;" required>
                                    <option value="">Select type</option>
                                    <option value="system" {{ old('type') == 'system' ? 'selected' : '' }}>System</option>
                                    <option value="order" {{ old('type') == 'order' ? 'selected' : '' }}>Order</option>
                                    <option value="message" {{ old('type') == 'message' ? 'selected' : '' }}>Message</option>
                                    <option value="listing" {{ old('type') == 'listing' ? 'selected' : '' }}>Listing</option>
                                    <option value="wishlist" {{ old('type') == 'wishlist' ? 'selected' : '' }}>Wishlist</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Notification Type (System-wide or Specific User) -->
                            <div class="mb-4">
                                <label for="notification_type" class="form-label" style="font-weight: 600; color: #374151;">Send To</label>
                                <select class="form-select @error('notification_type') is-invalid @enderror" 
                                        id="notification_type" name="notification_type" 
                                        style="border-radius: 8px; padding: 12px;" required>
                                    <option value="system" {{ old('notification_type') == 'system' ? 'selected' : '' }}>All Users (System-wide)</option>
                                    <option value="specific" {{ old('notification_type') == 'specific' ? 'selected' : '' }}>Specific User</option>
                                </select>
                                @error('notification_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- User Selection (shown only for specific user) -->
                            <div class="mb-4" id="user_id_field" style="display: none;">
                                <label for="user_id" class="form-label" style="font-weight: 600; color: #374151;">Select User</label>
                                <select class="form-select @error('user_id') is-invalid @enderror" 
                                        id="user_id" name="user_id" 
                                        style="border-radius: 8px; padding: 12px;">
                                    <option value="">Choose a user...</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->username }})
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Select the user to receive this notification</small>
                                @error('user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('notifications.index') }}" class="btn btn-outline-secondary" style="border-radius: 8px; padding: 10px 24px;">
                                    Cancel
                                </a>
                                <button type="submit" class="btn btn-success" style="border-radius: 8px; padding: 10px 24px;">
                                    <i class="lni lni-checkmark"></i> Create Notification
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Show/Hide User ID field based on notification type -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const notificationType = document.getElementById('notification_type');
            const userIdField = document.getElementById('user_id_field');
            const userIdInput = document.getElementById('user_id');

            function toggleUserIdField() {
                if (notificationType.value === 'specific') {
                    userIdField.style.display = 'block';
                    userIdInput.required = true;
                } else {
                    userIdField.style.display = 'none';
                    userIdInput.required = false;
                    userIdInput.value = '';
                }
            }

            // Initial check
            toggleUserIdField();

            // Listen for changes
            notificationType.addEventListener('change', toggleUserIdField);
        });
    </script>
@endsection
