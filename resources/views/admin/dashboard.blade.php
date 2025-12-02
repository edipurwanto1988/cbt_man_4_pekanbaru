@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <!-- PageHeading -->
    <header class="flex flex-wrap items-center justify-between gap-4 mb-8">
        <h1 class="text-gray-900 dark:text-white text-3xl font-bold leading-tight">Dashboard</h1>
        <button class="flex min-w-[84px] cursor-pointer items-center justify-center gap-2 overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-wide hover:bg-primary/90 transition-colors">
            <i class="ri-add-circle-line text-xl"></i>
            <span class="truncate">Buat Ujian Baru</span>
        </button>
    </header>

    <!-- Stats -->
    <section class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3 mb-8">
        <div class="flex flex-col gap-2 rounded-xl p-6 border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900/50">
            <p class="text-gray-600 dark:text-gray-400 text-base font-medium">Ujian Aktif</p>
            <p class="text-gray-900 dark:text-white text-3xl font-bold">{{ $aktifCount }}</p>
        </div>
        <div class="flex flex-col gap-2 rounded-xl p-6 border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900/50">
            <p class="text-gray-600 dark:text-gray-400 text-base font-medium">Siswa Terdaftar</p>
            <p class="text-gray-900 dark:text-white text-3xl font-bold">{{ $siswaCount }}</p>
        </div>
        <div class="flex flex-col gap-2 rounded-xl p-6 border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900/50">
            <p class="text-gray-600 dark:text-gray-400 text-base font-medium">Soal dalam Bank</p>
            <p class="text-gray-900 dark:text-white text-3xl font-bold">{{ $pertanyaanCount }}</p>
        </div>
    </section>

    <!-- Charts -->
    <section class="mb-8">
        <div class="flex min-w-72 flex-1 flex-col gap-4 rounded-xl border border-gray-200 dark:border-gray-800 p-6 bg-white dark:bg-gray-900/50">
            <p class="text-gray-900 dark:text-white text-lg font-bold">Aktivitas Ujian Terbaru</p>
            <div class="flex items-baseline gap-4">
                <p class="text-gray-900 dark:text-white text-4xl font-bold truncate">152</p>
                <div class="flex gap-1 items-center">
                    <p class="text-gray-500 dark:text-gray-400 text-sm">7 Hari Terakhir</p>
                    <p class="text-green-600 dark:text-green-500 text-sm font-medium">+15%</p>
                </div>
            </div>
            <div class="grid min-h-[180px] grid-flow-col gap-6 grid-rows-[1fr_auto] items-end justify-items-center">
                <div class="bg-primary/20 dark:bg-primary/30 w-full rounded-t-md" style="height: 70%;"></div>
                <p class="text-gray-500 dark:text-gray-400 text-xs font-bold">Sen</p>
                <div class="bg-primary/20 dark:bg-primary/30 w-full rounded-t-md" style="height: 30%;"></div>
                <p class="text-gray-500 dark:text-gray-400 text-xs font-bold">Sel</p>
                <div class="bg-primary/20 dark:bg-primary/30 w-full rounded-t-md" style="height: 40%;"></div>
                <p class="text-gray-500 dark:text-gray-400 text-xs font-bold">Rab</p>
                <div class="bg-primary/20 dark:bg-primary/30 w-full rounded-t-md" style="height: 40%;"></div>
                <p class="text-gray-500 dark:text-gray-400 text-xs font-bold">Kam</p>
                <div class="bg-primary w-full rounded-t-md" style="height: 85%;"></div>
                <p class="text-gray-900 dark:text-white text-xs font-bold">Sab</p>
                <div class="bg-primary/20 dark:bg-primary/30 w-full rounded-t-md" style="height: 20%;"></div>
                <p class="text-gray-500 dark:text-gray-400 text-xs font-bold">Min</p>
            </div>
        </div>
    </section>

    <!-- SectionHeader for Quick Actions -->
    <h2 class="text-gray-900 dark:text-white text-xl font-bold mb-4">Aksi Cepat</h2>
    <section class="grid grid-cols-1 gap-6 md:grid-cols-2">
        <a class="flex items-start gap-4 rounded-xl p-6 border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900/50 hover:border-primary dark:hover:border-primary transition-colors group" href="#">
            <div class="bg-primary/10 dark:bg-primary/20 text-primary p-3 rounded-lg flex items-center justify-center">
                <i class="ri-edit-line text-xl"></i>
            </div>
            <div class="flex flex-col">
                <h3 class="text-gray-900 dark:text-white font-bold mb-1 group-hover:text-primary">Kelola Ujian</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm">Buat, edit, dan pantau ujian secara real-time.</p>
            </div>
        </a>
        <a class="flex items-start gap-4 rounded-xl p-6 border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900/50 hover:border-primary dark:hover:border-primary transition-colors group" href="#">
            <div class="bg-primary/10 dark:bg-primary/20 text-primary p-3 rounded-lg flex items-center justify-center">
                <i class="ri-user-add-line text-xl"></i>
            </div>
            <div class="flex flex-col">
                <h3 class="text-gray-900 dark:text-white font-bold mb-1 group-hover:text-primary">Kelola Siswa</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm">Tambah, impor, dan lihat profil siswa dan pendaftaran.</p>
            </div>
        </a>
        <a class="flex items-start gap-4 rounded-xl p-6 border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900/50 hover:border-primary dark:hover:border-primary transition-colors group" href="#">
            <div class="bg-primary/10 dark:bg-primary/20 text-primary p-3 rounded-lg flex items-center justify-center">
                <i class="ri-book-line text-xl"></i>
            </div>
            <div class="flex flex-col">
                <h3 class="text-gray-900 dark:text-white font-bold mb-1 group-hover:text-primary">Kelola Bank Soal</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm">Tambah dan organisasi soal berdasarkan mata pelajaran, topik, dan kesulitan.</p>
            </div>
        </a>
        <a class="flex items-start gap-4 rounded-xl p-6 border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900/50 hover:border-primary dark:hover:border-primary transition-colors group" href="#">
            <div class="bg-primary/10 dark:bg-primary/20 text-primary p-3 rounded-lg flex items-center justify-center">
                <i class="ri-line-chart-line text-xl"></i>
            </div>
            <div class="flex flex-col">
                <h3 class="text-gray-900 dark:text-white font-bold mb-1 group-hover:text-primary">Lihat Hasil & Laporan</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm">Akses skor ujian detail dan analisis performa.</p>
            </div>
        </a>
    </section>
@endsection