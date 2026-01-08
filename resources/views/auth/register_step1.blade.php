@extends('auth.layout.app')

@section('title', 'Register - Step 1')

@section('content')
    <div class="max-w-xl">
        <div class="mb-6">
            <h3 class="text-2xl font-semibold text-slate-900">Create your account</h3>
            <p class="text-sm text-slate-500">Join PocketRader and start trading today</p>
        </div>

        <div class="mb-8">
            <div class="flex justify-between items-center relative">
                <!-- Line -->
                <div class="absolute w-full top-3 h-1 bg-indigo-600 z-0"></div>
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

        <form method="POST" action="{{ url('/register/step2') }}" class="space-y-5">
            @csrf

            <!-- Display validation errors -->
            @if ($errors->any())
                <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                    <ul class="text-sm text-red-600 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="space-y-2">
                <label class="text-sm font-medium text-slate-700" for="fullname">Name</label>
                <input type="text" id="fullname" name="fullname" placeholder="Name" required
                       class="w-full h-12 rounded-lg border border-slate-200 bg-white px-4 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-medium text-slate-700" for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Username" required
                       class="w-full h-12 rounded-lg border border-slate-200 bg-white px-4 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-medium text-slate-700" for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Email" required
                       class="w-full h-12 rounded-lg border border-slate-200 bg-white px-4 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-medium text-slate-700" for="phone">Phone</label>
                <input type="tel" id="phone" name="phone" placeholder="Phone" required
                       class="w-full h-12 rounded-lg border border-slate-200 bg-white px-4 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-medium text-slate-700" for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Password" required
                       class="w-full h-12 rounded-lg border border-slate-200 bg-white px-4 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-medium text-slate-700" for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Re-enter password" required
                       class="w-full h-12 rounded-lg border border-slate-200 bg-white px-4 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition">
            </div>

            <label class="flex items-center gap-3 text-sm text-slate-600">
                <input type="checkbox" required class="h-5 w-5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                I'm not robot
            </label>

            <button type="submit"
                    class="w-full h-12 rounded-lg bg-[#365AF7] text-white font-semibold shadow-md shadow-indigo-200 hover:bg-[#2f4ed6] transition">
                Sign Up
            </button>
        </form>
    </div>
@endsection