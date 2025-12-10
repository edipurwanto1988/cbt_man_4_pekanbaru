<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Peserta Login - CBT Application</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
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
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark font-display">
<div class="relative flex h-auto min-h-screen w-full flex-col group/design-root overflow-x-hidden">
    <div class="layout-container flex h-full grow flex-col">
        <main class="flex h-full min-h-screen w-full grow">
            <div class="grid w-full grid-cols-1 md:grid-cols-10">
                <div class="col-span-1 flex flex-col items-start bg-background-light px-6 py-10 dark:bg-background-dark md:col-span-4 lg:px-12">
                    <div class="w-full max-w-md space-y-8">
                        <div>
                            <div class="flex items-center gap-3 pb-6">
                                <img src="{{ asset('images/logo_clean-min.png') }}" alt="MAN 4 KOTA PEKANBARU" class="h-8 w-8">
                                <span class="text-xl font-bold text-[#0d121b] dark:text-white">MAN 4 KOTA PEKANBARU</span>
                            </div>
                            <div class="flex min-w-72 flex-col gap-3">
                                <p class="text-[#0d121b] text-4xl font-black leading-tight tracking-[-0.033em] dark:text-white">Participant Login</p>
                                <p class="text-[#4c669a] text-base font-normal leading-normal dark:text-slate-400">Please enter your credentials to begin.</p>
                            </div>
                        </div>
                        
                        @if ($errors->any())
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                                <ul class="list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        @if(session('error'))
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                                {{ session('error') }}
                            </div>
                        @endif
                        
                        <form action="{{ route('participant.login.post') }}" method="POST" class="flex flex-col gap-6">
                            @csrf
                            <label class="flex flex-col min-w-40 flex-1">
                                <p class="text-[#0d121b] text-base font-medium leading-normal pb-2 dark:text-slate-300">Student ID</p>
                                <input 
                                    type="text" 
                                    name="nisn"
                                    class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-[#0d121b] focus:outline-0 focus:ring-0 border border-[#cfd7e7] bg-white focus:border-primary h-14 placeholder:text-[#4c669a] p-[15px] text-base font-normal leading-normal dark:bg-slate-800 dark:border-slate-700 dark:text-white dark:placeholder:text-slate-400" 
                                    placeholder="Enter your Student ID" 
                                    value="{{ old('nisn') }}"
                                    required
                                />
                            </label>
                            <label class="flex flex-col min-w-40 flex-1">
                                <p class="text-[#0d121b] text-base font-medium leading-normal pb-2 dark:text-slate-300">Password</p>
                                <div class="flex w-full flex-1 items-stretch rounded-lg">
                                    <input 
                                        type="password" 
                                        name="password"
                                        class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-[#0d121b] focus:outline-0 focus:ring-0 border border-[#cfd7e7] bg-white focus:border-primary h-14 placeholder:text-[#4c669a] p-[15px] rounded-r-none border-r-0 pr-2 text-base font-normal leading-normal dark:bg-slate-800 dark:border-slate-700 dark:text-white dark:placeholder:text-slate-400" 
                                        placeholder="Enter your password" 
                                        required
                                    />
                                    <div class="text-[#4c669a] flex border border-[#cfd7e7] bg-white items-center justify-center px-[15px] rounded-r-lg border-l-0 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-400">
                                        <i class="ri-eye-line cursor-pointer" onclick="togglePassword()"></i>
                                    </div>
                                </div>
                            </label>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-red-600 dark:text-red-500"></span>
                                <a class="font-medium text-primary hover:underline" href="#">Help & Support</a>
                            </div>
                            <button type="submit" class="flex h-12 w-full items-center justify-center rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-background-light dark:focus:ring-offset-background-dark">
                                Login
                            </button>
                        </form>
                    </div>
                </div>
                <div class="col-span-1 hidden flex-col items-center justify-center bg-white p-10 dark:bg-slate-900 md:col-span-6 md:flex">
                    <div class="flex h-full w-full max-w-3xl flex-col">
                        <div class="flex flex-grow flex-col justify-center">
                            <div class="flex w-full flex-col gap-12">
                                <div class="w-full gap-4 rounded-lg flex flex-col">
                                    <div class="w-full bg-center bg-no-repeat bg-cover aspect-[2/1] rounded-xl flex-1" data-alt="Abstract gradient of blue and green shapes creating a calm and focused atmosphere for students." style='background-image: url({{ $logoBanner->value }});'></div>
                                    
                                    <div class="flex flex-col gap-3">
                                        <h1 class="text-[#0d121b] text-4xl font-black leading-tight tracking-[-0.033em] dark:text-white">Welcome, Participants!</h1>
                                        <p class="text-[#4c669a] text-lg font-normal leading-normal dark:text-slate-400">Your examination is about to begin. Please review the instructions carefully.</p>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                                    <div class="rounded-xl border border-slate-200 bg-background-light p-6 dark:border-slate-800 dark:bg-background-dark">
                                        <h3 class="flex items-center gap-2 text-lg font-bold text-[#0d121b] dark:text-white">
                                            <i class="ri-checkbox-circle-line text-primary"></i>
                                            Instruksi PreTest
                                        </h3>
                                            @php
                                                $instruksiPretest = \App\Models\Setting::getValue('Instruksi_Pretest', '<ul class="mt-4 list-disc space-y-2 pl-5"><li>Pastikan Anda memiliki koneksi internet yang stabil</li><li>Sistem menjawab soal per soal secara serentak</li><li>Setiap soal memiliki waktu tersendiri</li><li>Hubungi support jika mengalami masalah teknis</li></ul>');
                                                
                                                // Add CSS classes to the ul if they don't exist
                                                if (strpos($instruksiPretest, '<ul>') !== false && strpos($instruksiPretest, 'class=') === false) {
                                                    $instruksiPretest = str_replace('<ul>', '<ul class="mt-4 list-disc space-y-2 pl-5">', $instruksiPretest);
                                                }
                                            @endphp
                                            <div class="mt-4 text-[#4c669a] dark:text-slate-400">
                                                {!! $instruksiPretest !!}
                                            </div>
                                    </div>
                                    <div class="rounded-xl border border-slate-200 bg-background-light p-6 dark:border-slate-800 dark:bg-background-dark">
                                        <h3 class="flex items-center gap-2 text-lg font-bold text-[#0d121b] dark:text-white">
                                            <i class="ri-campaign-line text-primary"></i>
                                            Instruksi PostTest
                                        </h3>
                                        @php
                                            $instruksiPosttest = \App\Models\Setting::getValue('Instruksi_Posttest', '<ul class="mt-4 list-disc space-y-2 pl-5"><li>Pastikan Anda memiliki koneksi internet yang stabil</li><li>Hubungi support jika mengalami masalah teknis</li></ul>');
                                            
                                            // Add CSS classes to the ul if they don't exist
                                            if (strpos($instruksiPosttest, '<ul>') !== false && strpos($instruksiPosttest, 'class=') === false) {
                                                $instruksiPosttest = str_replace('<ul>', '<ul class="mt-4 list-disc space-y-2 pl-5">', $instruksiPosttest);
                                            }
                                        @endphp
                                        <div class="mt-4 text-[#4c669a] dark:text-slate-400">
                                            {!! $instruksiPosttest !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-8 text-center text-sm text-slate-500 dark:text-slate-400">
                            @php
                                $footerContent = \App\Models\Setting::getValue('Footer', 'For technical issues, please contact support at support@cbtplatform.edu or call (123) 456-7890.');
                            @endphp
                            <p>{!! $footerContent !!}</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
function togglePassword() {
    const passwordInput = document.querySelector('input[name="password"]');
    const icon = document.querySelector('.ri-eye-line, .ri-eye-off-line');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.className = 'ri-eye-off-line';
    } else {
        passwordInput.type = 'password';
        icon.className = 'ri-eye-line';
    }
}
</script>
</body>
</html>