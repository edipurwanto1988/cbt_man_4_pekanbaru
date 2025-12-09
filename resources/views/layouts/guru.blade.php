<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CBT Guru') - CBT Guru</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo_clean-min.png') }}">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&display=swap" rel="stylesheet"/>
    <!-- Remix Icons CSS -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet"/>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#2b6cee",
                        "background-light": "#f6f6f8",
                        "background-dark": "#101622",
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-display bg-background-light dark:bg-background-dark">
<div class="relative flex min-h-screen w-full">
    <!-- SideNavBar -->
    <aside class="sticky top-0 h-screen flex flex-col w-64 bg-white dark:bg-background-dark dark:border-r dark:border-gray-800 shadow-sm flex-shrink-0">
        <div class="flex h-full flex-col justify-between p-4">
            <div class="flex flex-col gap-8">
                <div class="flex items-center gap-3 px-3">
                    <div class="bg-primary rounded-lg p-2 text-white flex items-center justify-center">
                        <img src="{{ asset('images/logo_clean-min.png') }}" alt="Logo" class="h-8 w-8">
                    </div>
                    <h1 class="text-gray-900 dark:text-white text-lg font-bold">CBT Guru</h1>
                </div>
                <nav class="flex flex-col gap-2">
                    <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('guru.dashboard') ? 'bg-primary/10 text-primary dark:bg-primary/20' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}" href="{{ route('guru.dashboard') }}">
                        <i class="ri-home-line text-xl"></i>
                        <p class="text-sm font-medium">Dashboard</p>
                    </a>
                    
                    <!-- Rombel Menu -->
                    <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('guru.rombel*') ? 'bg-primary/10 text-primary dark:bg-primary/20' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}" href="{{ route('guru.rombel.index') }}">
                        <i class="ri-group-line text-xl"></i>
                        <p class="text-sm font-medium">Rombel</p>
                    </a>
                    
                    <!-- Bank Soal Menu -->
                    <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('guru.bank_soal*') ? 'bg-primary/10 text-primary dark:bg-primary/20' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}" href="{{ route('guru.bank_soal.index') }}">
                        <i class="ri-book-line text-xl"></i>
                        <p class="text-sm font-medium">Bank Soal</p>
                    </a>
                    
                    <!-- Jadwal Ujian Menu -->
                    <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('guru.jadwal_ujian*') ? 'bg-primary/10 text-primary dark:bg-primary/20' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}" href="{{ route('guru.jadwal_ujian.index') }}">
                        <i class="ri-calendar-line text-xl"></i>
                        <p class="text-sm font-medium">Jadwal Ujian</p>
                    </a>
                </nav>
            </div>
            <div class="flex flex-col gap-2 border-t border-gray-200 dark:border-gray-800 pt-4">
                <div class="flex items-center gap-3 px-3 py-2">
                    <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" style='background-image: url("https://picsum.photos/seed/user123/100/100.jpg")'></div>
                    <div class="flex flex-col">
                        <h2 class="text-gray-800 dark:text-gray-200 text-sm font-medium">{{ Auth::guard('guru')->user()->nama_guru }}</h2>
                        <p class="text-gray-500 dark:text-gray-400 text-xs">Guru</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('guru.logout') }}" class="px-3">
                    @csrf
                    <button type="submit" class="ml-auto text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                        <i class="ri-logout-box-line text-xl"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 p-6 lg:p-10 min-h-screen">
        <div class="mx-auto max-w-7xl">
            @yield('content')
        </div>
    </main>
</div>

@stack('scripts')
<!-- Footer -->
<footer class="w-full py-6 px-4 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
    <div class="mx-auto max-w-7xl items-center justify-center text-center">
       <p class="text-sm text-gray-500 dark:text-gray-400">
           Man 4 Pekanbaru©2025 - 2026
        </p>
    </div>
</footer>
</body>
</html>