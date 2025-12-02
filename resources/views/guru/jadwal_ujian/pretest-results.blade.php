@extends('layouts.guru')

@section('title', 'Hasil Pretest - ' . $session->bankSoal->nama_bank)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Hasil Pretest: {{ $session->bankSoal->nama_bank }}</h1>
            <p class="text-gray-600 dark:text-gray-400">Sesi: {{ $session->id }}</p>
        </div>
        <a href="{{ route('guru.jadwal_ujian.pretest') }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
            <i class="ri-arrow-left-line mr-2"></i>Kembali
        </a>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Peserta</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $rankings->count() }}</p>
                </div>
                <div class="bg-blue-100 dark:bg-blue-900/30 p-3 rounded-full">
                    <i class="ri-group-line text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Rata-rata Poin</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $rankings->avg('total_poin') }}</p>
                </div>
                <div class="bg-green-100 dark:bg-green-900/30 p-3 rounded-full">
                    <i class="ri-bar-chart-line text-green-600 dark:text-green-400 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Poin Tertinggi</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $rankings->max('total_poin') }}</p>
                </div>
                <div class="bg-yellow-100 dark:bg-yellow-900/30 p-3 rounded-full">
                    <i class="ri-trophy-line text-yellow-600 dark:text-yellow-400 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Waktu Rata-rata</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $rankings->avg('total_waktu_respon') }}s</p>
                </div>
                <div class="bg-purple-100 dark:bg-purple-900/30 p-3 rounded-full">
                    <i class="ri-time-line text-purple-600 dark:text-purple-400 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Rankings Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-4 text-white">
            <h2 class="text-xl font-bold">Peringkat Peserta</h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Peringkat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama Siswa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">NISN</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jawaban Benar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jawaban Salah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total Poin</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Waktu Respon</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($rankings as $ranking)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($ranking->peringkat == 1)
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-200">
                                        {{ $ranking->peringkat }}
                                    </span>
                                @elseif($ranking->peringkat == 2)
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                        {{ $ranking->peringkat }}
                                    </span>
                                @elseif($ranking->peringkat == 3)
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-200">
                                        {{ $ranking->peringkat }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200">
                                        {{ $ranking->peringkat }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <img src="{{ $ranking->siswa->foto ? asset('storage/uploads/siswa/' . $ranking->siswa->foto) : 'https://ui-avatars.com/api/?name=' . urlencode($ranking->siswa->nama_siswa ?? 'Unknown') . '&background=6366f1&color=ffffff&size=32' }}" 
                                         alt="{{ $ranking->siswa->nama_siswa }}"
                                         class="w-8 h-8 rounded-full mr-3"
                                         onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($ranking->siswa->nama_siswa ?? 'Unknown') }}&background=6366f1&color=ffffff&size=32'">
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $ranking->siswa->nama_siswa }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $ranking->siswa->nisn }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $ranking->total_benar }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $ranking->total_salah }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $ranking->total_poin }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $ranking->total_waktu_respon }}s</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Export Button -->
    <div class="flex justify-end">
        <button class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-6 rounded-lg font-medium transition">
            <i class="ri-download-line mr-2"></i> Export Hasil
        </button>
    </div>
</div>
@endsection