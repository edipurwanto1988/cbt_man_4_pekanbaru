@extends('layouts.admin')

@section('title', 'Manajemen Mata Pelajaran')

@section('content')
<!-- PageHeading -->
<header class="flex flex-wrap items-center justify-between gap-4 mb-8">
    <h1 class="text-gray-900 dark:text-white text-3xl font-bold leading-tight">Manajemen Mata Pelajaran</h1>
    <a href="{{ route('admin.mata_pelajaran.create') }}" class="flex min-w-[84px] cursor-pointer items-center justify-center gap-2 overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-wide hover:bg-primary/90 transition-colors">
        <i class="ri-add-circle-line text-xl"></i>
        <span class="truncate">Tambah Mata Pelajaran</span>
    </a>
</header>

<!-- Success/Error Messages -->
@if (session('success'))
    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
        {{ session('error') }}
    </div>
@endif

<!-- Mata Pelajaran Table -->
<div class="bg-white dark:bg-gray-900/50 rounded-xl border border-gray-200 dark:border-gray-800 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama Mata Pelajaran</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900/50 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($mataPelajaran as $index => $item)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white">{{ $mataPelajaran->firstItem() + $index }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center">
                                        <i class="ri-book-line text-primary"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $item->nama_mapel }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end items-center space-x-2">
                                <a href="{{ route('admin.mata_pelajaran.show', $item) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-200 transition-colors" title="Lihat Detail">
                                    <i class="ri-eye-line text-sm"></i>
                                </a>
                                <a href="{{ route('admin.mata_pelajaran.edit', $item) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-primary/10 text-primary hover:bg-primary/20 transition-colors" title="Edit">
                                    <i class="ri-edit-line text-sm"></i>
                                </a>
                                <form action="{{ route('admin.mata_pelajaran.destroy', $item) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-100 text-red-600 hover:bg-red-200 transition-colors" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus {{ $item->nama_mapel }}?')">
                                        <i class="ri-delete-bin-line text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                            Belum ada data mata pelajaran
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($mataPelajaran->hasPages())
        <div class="bg-white dark:bg-gray-900/50 px-4 py-3 flex items-center justify-between border-t border-gray-200 dark:border-gray-700 sm:px-6">
            <div class="flex-1 flex justify-between sm:hidden">
                {{ $mataPelajaran->links() }}
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700 dark:text-gray-300">
                        Menampilkan <span class="font-medium">{{ $mataPelajaran->firstItem() }}</span> hingga
                        <span class="font-medium">{{ $mataPelajaran->lastItem() }}</span> dari
                        <span class="font-medium">{{ $mataPelajaran->total() }}</span> hasil
                    </p>
                </div>
                <div>
                    {{ $mataPelajaran->links() }}
                </div>
            </div>
        </div>
    @endif
</div>
@endsection