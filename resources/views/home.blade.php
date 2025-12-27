@extends('auth.layout.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md">
        
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Welcome!</h1>
            <p class="text-gray-600 mt-2">You are logged in</p>
        </div>

        <!-- User Info -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
            <div class="mb-4">
                <label class="text-sm text-gray-600">User ID:</label>
                <p class="text-lg font-semibold text-gray-800">{{ $user_id ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="text-sm text-gray-600">Name:</label>
                <p class="text-lg font-semibold text-gray-800">{{ $user_name ?? 'Guest' }}</p>
            </div>
        </div>

        <!-- Placeholder Content -->
        <div class="mb-8 p-6 bg-gray-50 rounded-lg border border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Dashboard</h2>
            <p class="text-gray-600 mb-4">This is a placeholder home page. Your main content goes here.</p>
            <ul class="list-disc list-inside text-gray-600 space-y-2">
                <li>Browse your listings</li>
                <li>View your collection</li>
                <li>Check your wallet balance</li>
                <li>Manage your profile</li>
            </ul>
        </div>

        <!-- Logout Link -->
        <a href="{{ url('/logout') }}" class="block w-full text-center bg-red-500 hover:bg-red-600 text-white font-semibold py-3 rounded-lg transition duration-200">
            Logout
        </a>

    </div>
</div>
@endsection
