@extends('auth.layout.app')

@section('title', 'Login')

@section('content')

    <div id="url-message-container"></div>

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
            <p class="text-sm text-red-700">{{ session('error') }}</p>
        </div>
    @endif

    @if(session('warning'))
        <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
            <p class="text-sm text-yellow-700">{{ session('warning') }}</p>
        </div>
    @endif

    @if(session('info'))
        <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <p class="text-sm text-blue-700">{{ session('info') }}</p>
        </div>
    @endif

    <div class="max-w-xl">
        <form method="POST" action="/login" class="space-y-5">
            @csrf

            <div class="space-y-2">
                <label for="email" class="text-sm font-medium text-slate-700">Email</label>
                <input type="email" id="email" name="email" placeholder="Email" required autofocus
                       class="w-full h-12 rounded-lg border border-slate-200 bg-white px-4 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition">
            </div>

            <div class="space-y-2">
                <label for="password" class="text-sm font-medium text-slate-700">Password</label>
                <input type="password" id="password" name="password" placeholder="Password" required
                       class="w-full h-12 rounded-lg border border-slate-200 bg-white px-4 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition">
            </div>

            <label class="flex items-center gap-3 text-sm text-slate-600">
                <input type="checkbox" name="remember" class="h-5 w-5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                I'm not robot
            </label>

            <button type="submit"
                    class="w-full h-12 rounded-lg bg-[#365AF7] text-white font-semibold shadow-md shadow-indigo-200 hover:bg-[#2f4ed6] transition">
                Sign In
            </button>

            <p class="text-sm text-slate-600 text-center">
                Don't have an account?
                <a href="{{ url('/register/step1') }}" class="font-semibold text-indigo-600 hover:text-indigo-700">Sign Up</a>
            </p>
        </form>
    </div>

    <script>
        // Check for message in URL parameter
        const urlParams = new URLSearchParams(window.location.search);
        const message = urlParams.get('message');
        if (message) {
            const container = document.getElementById('url-message-container');
            container.innerHTML = `
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-sm text-red-700">${message}</p>
                </div>
            `;
            // Clean URL without reloading
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    </script>
@endsection