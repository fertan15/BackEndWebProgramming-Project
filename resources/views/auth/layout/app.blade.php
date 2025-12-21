<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PocketRader - @yield('title')</title>
    <!-- Load Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f5f7fb;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center px-4 py-8">
    <main class="w-full max-w-6xl bg-white shadow-xl rounded-2xl overflow-hidden border border-slate-100">
        <div class="grid grid-cols-1 md:grid-cols-2">
            <section class="relative bg-[#e7ebff] px-10 py-14 flex flex-col justify-center">
                <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
                    <div class="w-36 h-36 bg-white/50 rounded-full blur-3xl -top-10 -left-10 absolute"></div>
                    <div class="w-40 h-40 bg-indigo-200/60 rounded-full blur-3xl bottom-10 right-4 absolute"></div>
                </div>
                <div class="relative max-w-md text-center md:text-left">
                    <p class="text-sm font-semibold text-indigo-600 mb-4 uppercase tracking-wide">PocketRader</p>
                    <h1 class="text-4xl font-bold text-slate-900 leading-tight mb-4">Get Started</h1>
                    <p class="text-lg text-slate-600">Start creating the best possible user experience for your customers.</p>
                </div>
            </section>

            <section class="px-8 md:px-12 py-12 bg-white">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-2xl font-semibold text-slate-900">@yield('title')</h2>
                        <p class="text-sm text-slate-500">Seamless access to your trading workspace.</p>
                    </div>
                    <div class="text-right text-sm text-slate-500 hidden sm:block">
                        <a href="{{ url('/') }}" class="hover:text-indigo-600">Dashboard</a>
                        <span class="mx-1">/</span>
                        <span class="text-indigo-600 font-semibold">Auth</span>
                    </div>
                </div>

                <div class="flex gap-4 mb-6 text-sm font-medium">
                    <a href="{{ url('/login') }}" class="px-4 py-2 rounded-lg border transition-colors {{ request()->is('login') ? 'border-indigo-500 text-indigo-600 bg-indigo-50' : 'border-slate-200 text-slate-600 hover:border-indigo-200 hover:text-indigo-600' }}">Login</a>
                    <a href="{{ url('/register/step1') }}" class="px-4 py-2 rounded-lg border transition-colors {{ request()->is('register/*') ? 'border-indigo-500 text-indigo-600 bg-indigo-50' : 'border-slate-200 text-slate-600 hover:border-indigo-200 hover:text-indigo-600' }}">Register</a>
                </div>

                <div class="bg-slate-50 border border-slate-100 rounded-xl p-6 shadow-inner">
                    @yield('content')
                </div>
            </section>
        </div>
    </main>
</body>
</html>