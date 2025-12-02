@extends('layouts.admin')

@section('title', 'Detail Mata Pelajaran')

@section('content')
<!-- PageHeading -->
<header class="flex flex-wrap items-center justify-between gap-4 mb-8">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.mata_pelajaran.index') }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors" title="Kembali">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
            </svg>
        </a>
        <h1 class="text-gray-900 dark:text-white text-3xl font-bold leading-tight">Detail Mata Pelajaran</h1>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.mata_pelajaran.edit', $mataPelajaran) }}" class="flex min-w-[84px] cursor-pointer items-center justify-center gap-2 overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-wide hover:bg-primary/90 transition-colors">
            <i class="fas fa-edit"></i>
            <span class="truncate">Edit</span>
        </a>
    </div>
</header>

<!-- Detail Card -->
<div class="bg-white dark:bg-gray-900/50 rounded-xl border border-gray-200 dark:border-gray-800 overflow-hidden">
    <div class="p-6">
        <div class="flex items-center mb-6">
            <div class="flex-shrink-0 h-16 w-16">
                <div class="h-16 w-16 rounded-full bg-primary/10 flex items-center justify-center">
                    <i class="fas fa-book text-primary text-2xl"></i>
                </div>
            </div>
            <div class="ml-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $mataPelajaran->nama_mapel }}</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">ID: {{ $mataPelajaran->id }}</p>
            </div>
        </div>
        
        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID Mata Pelajaran</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $mataPelajaran->id }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Mata Pelajaran</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $mataPelajaran->nama_mapel }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat pada</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $mataPelajaran->created_at->format('d M Y, H:i') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Diperbarui pada</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $mataPelajaran->updated_at->format('d M Y, H:i') }}</dd>
                </div>
            </dl>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="mt-6 flex justify-end gap-3">
    <a href="{{ route('admin.mata_pelajaran.index') }}" 
        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
        Kembali
    </a>
    <a href="{{ route('admin.mata_pelajaran.edit', $mataPelajaran) }}" 
        class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors">
        Edit
    </a>
</div>
@endsection