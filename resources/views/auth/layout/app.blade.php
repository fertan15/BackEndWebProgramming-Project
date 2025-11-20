<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PocketRader - @yield('title')</title>
    <!-- Load Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom font and basic body style */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <main class="w-full max-w-lg mx-auto">
        <div class="text-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">PocketRader</h1>
            <p class="text-gray-500 mt-1">Card trading marketplace</p>
        </div>

        <!-- The Main Card Wrapper -->
        <div class="bg-white p-8 rounded-xl shadow-2xl border border-gray-100">
            <!-- Login/Register Tabs -->
            <div class="flex border-b border-gray-200 mb-6">
                <a href="{{ url('/login') }}" class="flex-1 text-center py-3 text-lg font-semibold transition-colors duration-200
                   {{ request()->is('login') ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-500 hover:text-indigo-500' }}">
                    Login
                </a>
                <a href="{{ url('/register/step1') }}" class="flex-1 text-center py-3 text-lg font-semibold transition-colors duration-200
                   {{ request()->is('register/*') ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-500 hover:text-indigo-500' }}">
                    Register
                </a>
            </div>

            <!-- Content Area for specific views -->
            @yield('content')

        </div>
    </main>
</body>
</html>