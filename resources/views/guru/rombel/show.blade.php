@extends('layouts.guru')

@section('title', 'Detail Rombel')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detail Rombel</h1>
            <p class="text-gray-600 dark:text-gray-400">Informasi detail rombongan belajar</p>
        </div>
        <a href="{{ route('guru.rombel.index') }}" 
           class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors flex items-center gap-2">
            <i class="ri-arrow-left-line"></i>
            Kembali
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            <div class="flex items-center gap-2">
                <i class="ri-checkbox-circle-line"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    <!-- Error Message -->
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
            <div class="flex items-center gap-2">
                <i class="ri-alert-line"></i>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <!-- Rombel Information -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
        <div class="flex items-start gap-6">
            <div class="h-16 w-16 flex-shrink-0 bg-primary/10 rounded-full flex items-center justify-center">
                <i class="ri-group-line text-primary text-2xl"></i>
            </div>
            <div class="flex-1">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $rombel->nama_rombel }}</h2>
                        <div class="mt-4 space-y-3">
                            <div class="flex items-center">
                                <i class="ri-building-line text-gray-400 mr-2"></i>
                                <span class="text-sm text-gray-600 dark:text-gray-400">Tingkat:</span>
                                <span class="ml-2 text-sm font-medium text-gray-900 dark:text-white">{{ $rombel->tingkatKelas->nama ?? '-' }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="ri-price-tag-3-line text-gray-400 mr-2"></i>
                                <span class="text-sm text-gray-600 dark:text-gray-400">Kode Kelas:</span>
                                <span class="ml-2">
                                    <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded-full">
                                        {{ $rombel->kode_kelas }}
                                    </span>
                                </span>
                            </div>
                            <div class="flex items-center">
                                <i class="ri-user-3-line text-gray-400 mr-2"></i>
                                <span class="text-sm text-gray-600 dark:text-gray-400">Wali Kelas:</span>
                                <span class="ml-2 text-sm font-medium text-gray-900 dark:text-white">{{ $rombel->waliKelas->nama_guru ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="mt-4 space-y-3">
                            <div class="flex items-center">
                                <i class="ri-calendar-line text-gray-400 mr-2"></i>
                                <span class="text-sm text-gray-600 dark:text-gray-400">Tahun Ajaran:</span>
                                <span class="ml-2 text-sm font-medium text-gray-900 dark:text-white">{{ $rombel->tahunAjaran->tahun_ajaran ?? '-' }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="ri-group-line text-gray-400 mr-2"></i>
                                <span class="text-sm text-gray-600 dark:text-gray-400">Jumlah Siswa:</span>
                                <span class="ml-2 text-sm font-medium text-gray-900 dark:text-white">{{ $rombel->rombelDetails->count() }} siswa</span>
                            </div>
                            <div class="flex items-center">
                                <i class="ri-book-line text-gray-400 mr-2"></i>
                                <span class="text-sm text-gray-600 dark:text-gray-400">Jumlah Mata Pelajaran:</span>
                                <span class="ml-2 text-sm font-medium text-gray-900 dark:text-white">{{ $rombel->rombelMapels->count() }} mapel</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <a href="{{ route('guru.rombel.siswa', $rombel->id) }}" 
           class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 hover:shadow-md transition-shadow flex items-center gap-4">
            <div class="h-12 w-12 flex-shrink-0 bg-blue-100 dark:bg-blue-900/20 rounded-full flex items-center justify-center">
                <i class="ri-graduation-cap-line text-blue-600 dark:text-blue-400 text-xl"></i>
            </div>
            <div>
                <h3 class="font-medium text-gray-900 dark:text-white">Data Siswa</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Lihat daftar siswa dalam rombel ini</p>
            </div>
            <div class="ml-auto">
                <i class="ri-arrow-right-line text-gray-400"></i>
            </div>
        </a>

        <a href="{{ route('guru.rombel.mapel', $rombel->id) }}" 
           class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 hover:shadow-md transition-shadow flex items-center gap-4">
            <div class="h-12 w-12 flex-shrink-0 bg-green-100 dark:bg-green-900/20 rounded-full flex items-center justify-center">
                <i class="ri-book-line text-green-600 dark:text-green-400 text-xl"></i>
            </div>
            <div>
                <h3 class="font-medium text-gray-900 dark:text-white">Mata Pelajaran</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Lihat daftar mata pelajaran dalam rombel ini</p>
            </div>
            <div class="ml-auto">
                <i class="ri-arrow-right-line text-gray-400"></i>
            </div>
        </a>
    </div>
</div>
@endsection