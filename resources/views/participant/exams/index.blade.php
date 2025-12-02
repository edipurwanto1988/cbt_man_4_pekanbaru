@extends('layouts.participant')

@section('content')
    <!-- PageHeading -->
    <header class="flex flex-wrap items-center justify-between gap-4 mb-8">
        <h1 class="text-gray-900 dark:text-white text-3xl font-bold leading-tight">Ujian Saya</h1>
        <a href="{{ route('participant.dashboard') }}" class="flex min-w-[84px] cursor-pointer items-center justify-center gap-2 overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-wide hover:bg-primary/90 transition-colors">
            <i class="ri-dashboard-line text-xl"></i>
            <span class="truncate">Dashboard</span>
        </a>
    </header>

    <!-- Stats -->
    <section class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3 mb-8">
        <div class="flex flex-col gap-2 rounded-xl p-6 border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900/50">
            <p class="text-gray-600 dark:text-gray-400 text-base font-medium">Total Ujian</p>
            <p class="text-gray-900 dark:text-white text-3xl font-bold">{{ $availableExams->count() }}</p>
        </div>
        <div class="flex flex-col gap-2 rounded-xl p-6 border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900/50">
            <p class="text-gray-600 dark:text-gray-400 text-base font-medium">Pretest</p>
            <p class="text-gray-900 dark:text-white text-3xl font-bold">{{ $availableExams->where('type_test', 'Pretest')->count() }}</p>
        </div>
        <div class="flex flex-col gap-2 rounded-xl p-6 border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900/50">
            <p class="text-gray-600 dark:text-gray-400 text-base font-medium">Posttest</p>
            <p class="text-gray-900 dark:text-white text-3xl font-bold">{{ $availableExams->where('type_test', 'Posttest')->count() }}</p>
        </div>
    </section>

    <!-- Alerts -->
    @if(session('error'))
        <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200 dark:bg-red-900/20 dark:border-red-800">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="ri-error-warning-line text-red-400 text-lg"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-800 dark:text-red-200">
                        {{ session('error') }}
                    </p>
                </div>
            </div>
        </div>
    @endif
    
    @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200 dark:bg-green-900/20 dark:border-green-800">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="ri-checkbox-circle-line text-green-400 text-lg"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-800 dark:text-green-200">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- SectionHeader for Exams -->
    <h2 class="text-gray-900 dark:text-white text-xl font-bold mb-4">Ujian Tersedia</h2>
    <section class="grid grid-cols-1 gap-6 md:grid-cols-2">
        @if($availableExams->count() > 0)
            @foreach($availableExams as $exam)
                <a class="flex items-start gap-4 rounded-xl p-6 border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900/50 hover:border-primary dark:hover:border-primary transition-colors group {{ strtolower($exam->type_test) === 'pretest' && !$exam->has_pretest_session ? 'opacity-70' : '' }}" href="{{ (strtolower($exam->type_test) === 'pretest' && $exam->has_pretest_session) ? route('participant.exams.take', $exam->id) : ((strtolower($exam->type_test) === 'posttest' && $exam->can_start) ? route('participant.exams.start', $exam->id) : '#') }}">
                    <div class="{{ strtolower($exam->type_test) === 'pretest' ? 'bg-blue-10 dark:bg-blue-900/30 text-blue-600' : (strtolower($exam->type_test) === 'posttest' ? 'bg-purple-10 dark:bg-purple-900/30 text-purple-600' : 'bg-primary/10 dark:bg-primary/20 text-primary') }} p-3 rounded-lg flex items-center justify-center">
                        <i class="ri-file-list-3-line text-xl"></i>
                    </div>
                    <div class="flex flex-col flex-1">
                        <div class="flex items-center justify-between">
                            <h3 class="text-gray-900 dark:text-white font-bold mb-1 group-hover:text-primary">{{ $exam->nama_bank }}</h3>
                            <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium {{ $exam->type_test == 'Pretest' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' : 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300' }}">
                                {{ $exam->type_test }}
                            </span>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-2">{{ $exam->nama_mapel }}</p>
                        <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                            <i class="ri-time-line mr-1"></i>
                            <span>{{ $exam->tanggal_mulai ? \Carbon\Carbon::parse($exam->tanggal_mulai)->format('d M Y, H:i') : 'Belum ditentukan' }}</span>
                        </div>
                        
                        @if(strtolower($exam->type_test) === 'pretest' && !$exam->has_pretest_session)
                            <div class="mt-2 text-xs text-amber-600 dark:text-amber-400">
                                <i class="ri-time-line mr-1"></i> Pretest belum dimulai
                            </div>
                        @elseif(strtolower($exam->type_test) === 'posttest' && !$exam->can_start)
                            <div class="mt-2 text-xs text-amber-600 dark:text-amber-400">
                                <i class="ri-time-line mr-1"></i> Waktu ujian belum tersedia
                            </div>
                        @endif
                    </div>
                </a>
            @endforeach
        @else
            <div class="col-span-full">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-12 text-center border border-gray-200 dark:border-gray-700">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 dark:bg-blue-900/30 mb-4">
                        <i class="ri-file-list-3-line text-blue-600 dark:text-blue-400 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Tidak Ada Ujian</h3>
                    <p class="text-gray-600 dark:text-gray-400 max-w-md mx-auto">
                        Saat ini tidak ada ujian yang tersedia untuk Anda. Silakan periksa kembali nanti.
                    </p>
                </div>
            </div>
        @endif
    </section>
@endsection