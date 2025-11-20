@extends('auth.layout.app')

@section('title', 'Register - Step 1')

@section('content')
    <h2 class="text-xl font-semibold text-gray-800 mb-2">Create your account</h2>
    <p class="text-sm text-gray-500 mb-6">Join PocketRader and start trading today</p>

    <!-- Step Progress Indicator -->
    <div class="mb-8">
        <div class="flex justify-between items-center relative">
            <!-- Line -->
            <div class="absolute w-full top-3 h-1 bg-indigo-200 z-0"></div>
            <!-- Steps -->
            <div class="flex flex-col items-center z-10">
                <div class="w-7 h-7 flex items-center justify-center rounded-full bg-indigo-600 text-white font-bold text-xs shadow-md">1</div>
                <span class="text-xs font-medium text-indigo-600 mt-2 text-center">Information<br><span class="text-gray-400">Basic Details</span></span>
            </div>
            <div class="flex flex-col items-center z-10">
                <div class="w-7 h-7 flex items-center justify-center rounded-full bg-white border-2 border-indigo-200 text-indigo-600 font-bold text-xs shadow-sm">2</div>
                <span class="text-xs font-medium text-gray-500 mt-2 text-center">Verification<br><span class="text-gray-400">OTP code</span></span>
            </div>
            <div class="flex flex-col items-center z-10">
                <div class="w-7 h-7 flex items-center justify-center rounded-full bg-white border-2 border-indigo-200 text-indigo-600 font-bold text-xs shadow-sm">3</div>
                <span class="text-xs font-medium text-gray-500 mt-2 text-center">Identification<br><span class="text-gray-400">Verify identity</span></span>
            </div>
        </div>
    </div>
    
    <form method="POST" action="{{ url('/register/step2') }}">
        @csrf
        
        <div class="space-y-4">
            <input type="text" name="fullname" placeholder="Full name (e.g., John Doe)" required
                   class="w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition duration-150">
            
            <input type="text" name="username" placeholder="Username (e.g., cardcollector123)" required
                   class="w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition duration-150">
            
            <input type="email" name="email" placeholder="Email (e.g., you@example.com)" required
                   class="w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition duration-150">
            
            <input type="tel" name="phone" placeholder="Phone (e.g., +1 (555) 000-0000)" required
                   class="w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition duration-150">
            
            <div class="relative">
                <input type="password" name="password" placeholder="Create a strong password" required
                       class="w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 pr-10">
                <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 cursor-pointer">
                    <!-- Icon for password visibility toggle -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                </span>
            </div>
            
            <div class="relative">
                <input type="password" name="password_confirmation" placeholder="Re-enter your password" required
                       class="w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 pr-10">
                <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 cursor-pointer">
                    <!-- Icon for password visibility toggle -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                </span>
            </div>
        </div>

        <button type="submit"
                class="w-full mt-6 bg-indigo-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500 focus:ring-opacity-50 transition duration-150 shadow-lg shadow-indigo-200">
            Continue
        </button>
    </form>
@endsection