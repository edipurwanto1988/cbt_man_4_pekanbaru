<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>@yield('title', 'CBT Admin') - CBT Admin</title>
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
</head>
<body class="font-display bg-background-light dark:bg-background-dark">
<div class="relative flex min-h-screen w-full">
    <!-- SideNavBar -->
    <aside class="sticky top-0 h-screen flex flex-col w-64 bg-white dark:bg-background-dark dark:border-r dark:border-gray-800 shadow-sm">
        <div class="flex h-full flex-col justify-between p-4">
            <div class="flex flex-col gap-8">
                <div class="flex items-center gap-3 px-3">
                    <div class="bg-primary rounded-lg p-2 text-white flex items-center justify-center">
                        <img src="{{ asset('images/logo_clean-min.png') }}" alt="Logo" class="h-8 w-8">
                    </div>
                    <h1 class="text-gray-900 dark:text-white text-lg font-bold">CBT Admin</h1>
                </div>
                <nav class="flex flex-col gap-2">
                    <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-primary/10 text-primary dark:bg-primary/20' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}" href="{{ route('admin.dashboard') }}">
                        <i class="ri-home-line text-xl"></i>
                        <p class="text-sm font-medium">Dashboard</p>
                    </a>
                    
                    <!-- Role Admin Menu -->
                    @if(Auth::guard('admin')->user()->role === 'admin')
                        <!-- Pengguna Menu -->
                        <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.admins*') ? 'bg-primary/10 text-primary dark:bg-primary/20' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}" href="{{ route('admin.admins.index') }}">
                            <i class="ri-group-line text-xl"></i>
                            <p class="text-sm font-medium">Pengguna</p>
                        </a>
                        
                        <!-- Master Menu with Toggle Submenu -->
                        <div>
                            <input type="checkbox" id="masterToggle" class="hidden" onchange="toggleSubmenu('master')">
                            <label for="masterToggle" class="flex items-center justify-between px-3 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                                <div class="flex items-center gap-3">
                                    <i class="ri-database-2-line text-xl text-gray-600 dark:text-gray-400"></i>
                                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Master</p>
                                </div>
                                <i id="masterIcon" class="ri-arrow-down-s-line text-gray-500 dark:text-gray-400 transition-transform duration-200"></i>
                            </label>
                            <div id="masterSubmenu" class="hidden space-y-1 pl-4 mt-1">
                                <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.guru*') ? 'bg-primary/10 text-primary dark:bg-primary/20' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }} transition-colors" href="{{ route('admin.guru.index') }}">
                                    <i class="ri-user-star-line text-xl"></i>
                                    <p class="text-sm font-medium">Guru</p>
                                </a>
                                <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.mata_pelajaran*') ? 'bg-primary/10 text-primary dark:bg-primary/20' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }} transition-colors" href="{{ route('admin.mata_pelajaran.index') }}">
                                    <i class="ri-book-line text-xl"></i>
                                    <p class="text-sm font-medium">Mata Pelajaran</p>
                                </a>
                                <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.tahun_ajaran*') ? 'bg-primary/10 text-primary dark:bg-primary/20' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }} transition-colors" href="{{ route('admin.tahun_ajaran.index') }}">
                                    <i class="ri-calendar-line text-xl"></i>
                                    <p class="text-sm font-medium">Tahun Ajaran</p>
                                </a>
                                <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.siswa*') ? 'bg-primary/10 text-primary dark:bg-primary/20' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }} transition-colors" href="{{ route('admin.siswa.index') }}">
                                    <i class="ri-graduation-cap-line text-xl"></i>
                                    <p class="text-sm font-medium">Siswa</p>
                                </a>
                                <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.rombel*') ? 'bg-primary/10 text-primary dark:bg-primary/20' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }} transition-colors" href="{{ route('admin.rombel.index') }}">
                                    <i class="ri-group-line text-xl"></i>
                                    <p class="text-sm font-medium">Rombel</p>
                                </a>
                                
                             
                            </div>
                        </div>
                        
                        <!-- Settings Menu -->
                        <a class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors" href="/admin/settings">
                            <i class="ri-settings-3-line text-xl"></i>
                            <p class="text-sm font-medium">Settings</p>
                        </a>
                        
                        <!-- Logout Menu -->
                        <form method="POST" action="{{ route('admin.logout') }}" class="contents">
                            @csrf
                            <button type="submit" class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors w-full text-left">
                                <i class="ri-logout-box-line text-xl"></i>
                                <p class="text-sm font-medium">Logout</p>
                            </button>
                        </form>
                    @endif
                    
                    <!-- Role Pengawas Menu -->
                    @if(Auth::guard('admin')->user()->role === 'pengawas')
                        <a class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800" href="#">
                            <i class="ri-calendar-line text-xl"></i>
                            <p class="text-sm font-medium">Jadwal Ujian</p>
                        </a>
                    @endif
                    
                    <!-- Role Guru Menu -->
                    @if(Auth::guard('admin')->user()->role === 'guru')
                        <!-- Rombel Menu -->
                        <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.rombel*') ? 'bg-primary/10 text-primary dark:bg-primary/20' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}" href="{{ route('admin.rombel.index') }}">
                            <i class="ri-group-line text-xl"></i>
                            <p class="text-sm font-medium">Rombel</p>
                        </a>
                        
                        <!-- Jadwal Ujian Menu -->
                        <a class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800" href="#">
                            <i class="ri-calendar-line text-xl"></i>
                            <p class="text-sm font-medium">Jadwal Ujian</p>
                        </a>
                    @endif
                </nav>
            </div>
            <div class="flex flex-col gap-2 border-t border-gray-200 dark:border-gray-800 pt-4">
                <div class="flex items-center gap-3 px-3 py-2">
                    <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" style='background-image: url("https://picsum.photos/seed/user123/100/100.jpg")'></div>
                    <div class="flex flex-col">
                        <h2 class="text-gray-800 dark:text-gray-200 text-sm font-medium">{{ Auth::guard('admin')->user()->nama_guru ?? Auth::guard('admin')->user()->name }}</h2>
                        <p class="text-gray-500 dark:text-gray-400 text-xs">{{ Auth::guard('admin')->user()->role === 'guru' ? 'Guru' : ucfirst(Auth::guard('admin')->user()->role) }}</p>
                    </div>
                </div>
              
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 p-6 lg:p-10">
        <div class="mx-auto max-w-7xl">
            @yield('content')
        </div>
    </main>
</div>

@stack('scripts')
<!-- CKEditor -->
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.0/classic/ckeditor.js"></script>
<!-- Footer -->
<footer class="w-full py-6 px-4 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
    <div class="mx-auto max-w-7xl items-center justify-center text-center">
        <p class="text-sm text-gray-500 dark:text-gray-400">
            @php
                $footerText = \App\Models\Setting::getValue('Footer', '© ' . date('Y') . ' CBT Admin Portal. Semua hak dilindungi. | <a class="hover:underline text-primary" href="#">Dukungan Teknis</a>');
            @endphp
            {!! $footerText !!}
        </p>
    </div>
</footer>

<script>
function toggleSubmenu(submenuId) {
    const submenu = document.getElementById(submenuId + 'Submenu');
    const icon = document.getElementById(submenuId + 'Icon');
    
    if (submenu.classList.contains('hidden')) {
        submenu.classList.remove('hidden');
        icon.classList.add('rotate-180');
    } else {
        submenu.classList.add('hidden');
        icon.classList.remove('rotate-180');
    }
}

// Close submenus when clicking outside
document.addEventListener('click', function(event) {
    // Handle master submenu
    const masterSubmenu = document.getElementById('masterSubmenu');
    const masterToggle = document.getElementById('masterToggle');
    const masterIcon = document.getElementById('masterIcon');
    
    if (masterSubmenu && masterToggle && masterIcon &&
        !masterSubmenu.contains(event.target) &&
        !masterToggle.contains(event.target) &&
        !masterIcon.contains(event.target)) {
        masterSubmenu.classList.add('hidden');
        masterIcon.classList.remove('rotate-180');
        masterToggle.checked = false;
    }
    
});
</script>
</body>
</html>