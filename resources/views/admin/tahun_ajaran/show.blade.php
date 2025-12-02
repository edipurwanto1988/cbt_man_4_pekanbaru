@extends('layouts.admin')

@section('title', 'Detail Tahun Ajaran')

@section('content')
<!-- PageHeading -->
<header class="flex flex-wrap items-center justify-between gap-4 mb-8">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.tahun_ajaran.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <h1 class="text-gray-900 dark:text-white text-3xl font-bold leading-tight">Detail Tahun Ajaran</h1>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.tahun_ajaran.edit', $tahunAjaran) }}" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors flex items-center">
            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793A2 2 0 0116 4.414V11a2 2 0 01-2 2H6a2 2 0 01-2-2V4.414a2 2 0 01.586-1.414l3.171-3.171a1 1 0 011.414 0l3.415 3.415z"/>
            </svg>
            Edit
        </a>
        <form action="{{ route('admin.tahun_ajaran.destroy', $tahunAjaran) }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center" onclick="return confirm('Apakah Anda yakin ingin menghapus {{ $tahunAjaran->tahun_ajaran }}?')">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                Hapus
            </button>
        </form>
    </div>
</header>

<!-- Tahun Ajaran Details -->
<div class="bg-white dark:bg-gray-900/50 rounded-xl border border-gray-200 dark:border-gray-800 p-6">
    <div class="flex items-center gap-6 mb-6">
        <div class="h-24 w-24 rounded-full bg-primary/10 flex items-center justify-center">
            <i class="fas fa-calendar-alt text-primary text-3xl"></i>
        </div>
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $tahunAjaran->tahun_ajaran }}</h2>
            <p class="text-gray-600 dark:text-gray-400">Tahun Ajaran</p>
            <div class="flex gap-2 mt-2">
                <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full
                    @if($tahunAjaran->semester == 1) bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                    @else bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                    @endif">
                    {{ $tahunAjaran->semester == 1 ? 'Ganjil' : 'Genap' }}
                </span>
                <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full
                    @if($tahunAjaran->status) bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                    @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                    @endif">
                    {{ $tahunAjaran->status ? 'Aktif' : 'Tidak Aktif' }}
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Informasi Tahun Ajaran</h3>
            <div class="space-y-3">
                <div class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                    <span class="text-gray-600 dark:text-gray-400">ID</span>
                    <span class="text-gray-900 dark:text-white font-medium">{{ $tahunAjaran->id }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                    <span class="text-gray-600 dark:text-gray-400">Tahun Ajaran</span>
                    <span class="text-gray-900 dark:text-white font-medium">{{ $tahunAjaran->tahun_ajaran }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                    <span class="text-gray-600 dark:text-gray-400">Semester</span>
                    <span class="text-gray-900 dark:text-white font-medium">{{ $tahunAjaran->semester == 1 ? 'Ganjil' : 'Genap' }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                    <span class="text-gray-600 dark:text-gray-400">Status</span>
                    <span class="text-gray-900 dark:text-white font-medium">{{ $tahunAjaran->status ? 'Aktif' : 'Tidak Aktif' }}</span>
                </div>
            </div>
        </div>

        <div class="space-y-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Informasi Tambahan</h3>
            <div class="space-y-3">
                <div class="py-2 border-b border-gray-200 dark:border-gray-700">
                    <span class="text-gray-600 dark:text-gray-400 block mb-2">Keterangan</span>
                    <span class="text-gray-900 dark:text-white">{{ $tahunAjaran->keterangan ?: 'Tidak ada keterangan' }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informasi Waktu</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                <span class="text-gray-600 dark:text-gray-400">Dibuat</span>
                <span class="text-gray-900 dark:text-white font-medium">{{ $tahunAjaran->created_at->format('d M Y H:i') }}</span>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                <span class="text-gray-600 dark:text-gray-400">Diperbarui</span>
                <span class="text-gray-900 dark:text-white font-medium">{{ $tahunAjaran->updated_at->format('d M Y H:i') }}</span>
            </div>
        </div>
    </div>
</div>
@endsection