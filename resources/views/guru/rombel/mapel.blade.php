@extends('layouts.guru')

@section('title', 'Mata Pelajaran Rombel')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Mata Pelajaran Rombel</h1>
            <p class="text-gray-600 dark:text-gray-400">Daftar mata pelajaran dalam {{ $rombel->nama_rombel }}</p>
        </div>
        <a href="{{ route('guru.rombel.show', $rombel->id) }}" 
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

    <!-- Rombel Info -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4">
        <div class="flex items-center gap-4">
            <div class="h-12 w-12 flex-shrink-0 bg-primary/10 rounded-full flex items-center justify-center">
                <i class="ri-group-line text-primary text-xl"></i>
            </div>
            <div>
                <h2 class="text-lg font-medium text-gray-900 dark:text-white">{{ $rombel->nama_rombel }}</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ $rombel->tingkatKelas->nama ?? '-' }} - Kelas {{ $rombel->kode_kelas }} - {{ $rombel->tahunAjaran->tahun_ajaran ?? '-' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-visible relative">
        <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            No
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Kode Mapel
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Mata Pelajaran
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Kelompok
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($rombel->rombelMapels as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors align-top">
                            <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $loop->index + 1 }}
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $item->mataPelajaran->kode_mapel ?? '-' }}
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0 bg-primary/10 rounded-full flex items-center justify-center">
                                        <i class="ri-book-line text-primary"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $item->mataPelajaran->nama_mapel ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                <span class="px-2 py-1 text-xs font-medium 
                                    {{ $item->mataPelajaran->kelompok == 'A' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' : '' }}
                                    {{ $item->mataPelajaran->kelompok == 'B' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                                    {{ $item->mataPelajaran->kelompok == 'C' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : '' }}
                                    rounded-full">
                                    Kelompok {{ $item->mataPelajaran->kelompok ?? '-' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-4">
                                    <i class="ri-book-line text-4xl text-gray-300 dark:text-gray-600"></i>
                                    <p class="text-gray-500 dark:text-gray-400">Belum ada mata pelajaran dalam rombel ini</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection