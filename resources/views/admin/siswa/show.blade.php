@extends('layouts.admin')

@section('title', 'Detail Siswa')

@section('content')
<!-- PageHeading -->
<header class="flex flex-wrap items-center justify-between gap-4 mb-8">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.siswa.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
            <i class="ri-arrow-left-line text-xl"></i>
        </a>
        <h1 class="text-gray-900 dark:text-white text-3xl font-bold leading-tight">Detail Siswa</h1>
    </div>
</header>

<!-- Siswa Info -->
<div class="bg-white dark:bg-gray-900/50 rounded-xl border border-gray-200 dark:border-gray-800 p-6">
    <div class="flex items-center gap-6 mb-6">
        <div class="flex-shrink-0 h-20 w-20">
            <div class="h-20 w-20 rounded-full bg-primary/10 flex items-center justify-center">
                <i class="ri-user-line text-primary text-2xl"></i>
            </div>
        </div>
        <div class="flex-1">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $siswa->nama_siswa }}</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-gray-500 dark:text-gray-400">NISN:</span>
                    <span class="ml-2 text-gray-900 dark:text-white">{{ $siswa->nisn }}</span>
                </div>
                <div>
                    <span class="text-gray-500 dark:text-gray-400">Jenis Kelamin:</span>
                    <span class="ml-2 text-gray-900 dark:text-white">{{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                </div>
                <div>
                    <span class="text-gray-500 dark:text-gray-400">Email:</span>
                    <span class="ml-2 text-gray-900 dark:text-white">{{ $siswa->email ?: '-' }}</span>
                </div>
                <div>
                    <span class="text-gray-500 dark:text-gray-400">Dibuat:</span>
                    <span class="ml-2 text-gray-900 dark:text-white">{{ $siswa->created_at->format('d M Y H:i') }}</span>
                </div>
                <div>
                    <span class="text-gray-500 dark:text-gray-400">Diperbarui:</span>
                    <span class="ml-2 text-gray-900 dark:text-white">{{ $siswa->updated_at->format('d M Y H:i') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection