@extends('auth.layout.app')

@section('title', 'Login')

@section('content')
    <h2 class="text-xl font-semibold text-gray-800 mb-6">Welcome back</h2>
    <p class="text-sm text-gray-500 mb-6">Enter your credentials to access your account</p>

    <form method="POST" action="/login">
        @csrf
        
        <div class="mb-4">
            <label for="email" class="sr-only">Email</label>
            <input type="email" id="email" name="email" placeholder="you@example.com"
                   required autofocus
                   class="w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition duration-150"
                   value="you@example.com" <!-- Mock Data -->
            >
        </div>

        <div class="mb-6 relative">
            <label for="password" class="sr-only">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password"
                   required
                   class="w-full p-3 border border-gray-300 rounded-lg pr-24 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150"
            >
            <a href="#" class="absolute right-0 top-1/2 transform -translate-y-1/2 mr-3 text-sm text-indigo-600 hover:text-indigo-500 font-medium">
                Forgot password?
            </a>
        </div>

        <button type="submit"
                class="w-full bg-indigo-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500 focus:ring-opacity-50 transition duration-150 shadow-lg shadow-indigo-200">
            Sign In
        </button>
    </form>
@endsection