<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>CBT Guru Portal - Login</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <!-- Remix Icons CSS -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet"/>
    <script id="tailwind-config">
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
            borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px"},
          },
        },
      }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-gray-800 dark:text-gray-200">
<div class="relative flex min-h-screen w-full flex-col overflow-x-hidden group/design-root">
    <div class="layout-container flex h-full grow flex-col">
        <header class="absolute top-0 left-0 right-0 z-10 p-6 sm:p-8">
            <div class="mx-auto flex max-w-7xl items-center justify-between">
                <div class="flex items-center gap-3 text-gray-900 dark:text-white">
                    <div class="size-6 text-primary">
                        <i class="ri-graduation-cap-line text-2xl"></i>
                    </div>
                    <h2 class="text-lg font-bold leading-tight tracking-[-0.015em]">CBT Guru Portal</h2>
                </div>
            </div>
        </header>
        <main class="flex flex-1 items-center justify-center py-16 px-4">
            <div class="w-full max-w-md space-y-8">
                <div class="text-center">
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Guru Login</h1>
                    <p class="mt-2 text-base text-gray-600 dark:text-gray-400">Sign in to manage your classes and exams.</p>
                </div>
                <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800/50 p-6 sm:p-8 shadow-sm">
                    <form method="POST" action="{{ route('guru.login.post') }}" class="space-y-6">
                        @csrf
                        <div class="space-y-2">
                            <label class="text-sm font-medium leading-none text-gray-700 dark:text-gray-300" for="email">Email/NIK</label>
                            <input class="flex h-12 w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-3 py-2 text-sm placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-background-light dark:focus:ring-offset-background-dark dark:bg-gray-900/50 @error('email') border-red-500 @enderror"
                                   id="email"
                                   name="email"
                                   placeholder="Masukkan email atau NIK Anda"
                                   type="text"
                                   value="{{ old('email') }}"
                                   required>
                            @error('email')
                                <p class="text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <label class="text-sm font-medium leading-none text-gray-700 dark:text-gray-300" for="password">Kata Sandi</label>
                                <a class="text-sm font-medium text-primary hover:text-primary/80 transition-colors" href="#">Lupa Kata Sandi?</a>
                            </div>
                            <div class="relative">
                                <input class="flex h-12 w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-3 py-2 text-sm placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-background-light dark:focus:ring-offset-background-dark dark:bg-gray-900/50 pr-10 @error('password') border-red-500 @enderror" 
                                       id="password" 
                                       name="password" 
                                       placeholder="Masukkan kata sandi Anda" 
                                       type="password" 
                                       required>
                                <button class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200" 
                                         type="button" 
                                         onclick="togglePassword()">
                                    <i class="ri-eye-line text-xl" id="passwordToggle"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex items-center">
                            <input class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary" 
                                   id="remember" 
                                   name="remember" 
                                   type="checkbox">
                            <label class="ml-2 text-sm text-gray-600 dark:text-gray-400" for="remember">Ingat saya</label>
                        </div>
                        <div>
                            <button class="flex h-12 w-full items-center justify-center rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-background-light dark:focus:ring-offset-background-dark" 
                                     type="submit">
                                Masuk
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
        <footer class="w-full py-6 px-4 sm:px-8">
            <div class="mx-auto flex max-w-7xl items-center justify-center text-center">
                <p class="text-sm text-gray-500 dark:text-gray-400">© {{ date('Y') }} CBT Guru Portal. All rights reserved. | <a class="hover:underline" href="#">Technical Support</a></p>
            </div>
        </footer>
    </div>
</div>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const passwordToggle = document.getElementById('passwordToggle');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            passwordToggle.className = 'ri-eye-off-line text-xl';
        } else {
            passwordInput.type = 'password';
            passwordToggle.className = 'ri-eye-line text-xl';
        }
    }
</script>
</body>
</html>