@extends('layouts.admin')

@section('title', 'Hasil Pretest - ' . $session->bankSoal->nama_bank)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Hasil Pretest: {{ $session->bankSoal->nama_bank }}</h1>
            <p class="text-gray-600 dark:text-gray-400">Sesi: {{ $session->id }}</p>
        </div>
        <a href="{{ route('admin.jadwal_ujian.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition-colors duration-200">
            <i class="ri-arrow-left-line mr-2"></i>Kembali ke Jadwal Ujian
        </a>
    </div>

    <!-- Podium - Top 3 -->
    @if($rankings->count() > 0)
    <div class="bg-gradient-to-br from-purple-50 to-blue-50 dark:from-gray-800 dark:to-gray-900 rounded-xl shadow-lg p-8">
        <h2 class="text-2xl font-bold text-center text-gray-900 dark:text-white mb-8">
            🏆 Top 3 Peserta Terbaik
        </h2>
        
        <div class="flex items-end justify-center gap-6 max-w-4xl mx-auto">
            <!-- 2nd Place -->
            @if(isset($rankings[1]))
            <div class="flex flex-col items-center justify-end" style="flex: 0 0 280px;">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl p-6 w-full transform hover:scale-105 transition-transform duration-200">
                    <div class="flex flex-col items-center">
                        <div class="relative mb-3">
                            <img src="https://api.dicebear.com/7.x/big-ears/svg?seed={{ urlencode($rankings[0]->siswa->nama_siswa ?? 'Unknown') }}&background=6366f1&color=ffffff&size=32" 
                                 alt="{{ $rankings[1]->siswa->nama_siswa }}"
                                 class="w-20 h-20 rounded-full border-4 border-gray-300 dark:border-gray-600 object-cover"
                                 onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($rankings[1]->siswa->nama_siswa ?? 'Unknown') }}&background=c0c0c0&color=ffffff&size=96'">
                            <div class="absolute -top-2 -right-2 bg-gray-400 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold text-sm shadow-md">
                                2
                            </div>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white text-center line-clamp-2">{{ $rankings[1]->siswa->nama_siswa }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $rankings[1]->nisn }}</p>
                        <div class="mt-3 bg-gray-100 dark:bg-gray-700 rounded-lg px-4 py-2 w-full text-center">
                            <p class="text-2xl font-bold text-gray-700 dark:text-gray-300">{{ $rankings[1]->total_poin }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">poin</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- 1st Place -->
            @if(isset($rankings[0]))
            <div class="flex flex-col items-center justify-end -mb-8" style="flex: 0 0 300px;">
                <div class="mb-10 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-xl shadow-2xl p-6 w-full transform hover:scale-105 transition-transform duration-200">
                    <div class="flex flex-col items-center">
                        <div class="relative mb-3">
                            <img src="https://api.dicebear.com/7.x/big-ears/svg?seed={{ urlencode($results[0]->siswa->nama_siswa ?? 'Unknown') }}&background=6366f1&color=ffffff&size=32" 
                                 alt="{{ $rankings[0]->siswa->nama_siswa }}"
                                 class="w-24 h-24 rounded-full border-4 border-yellow-300 object-cover"
                                 onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($rankings[0]->siswa->nama_siswa ?? 'Unknown') }}&background=ffd700&color=ffffff&size=96'">
                            <div class="absolute -top-2 -right-2 bg-yellow-600 text-white rounded-full w-10 h-10 flex items-center justify-center font-bold shadow-lg">
                                <i class="ri-trophy-fill text-xl"></i>
                            </div>
                        </div>
                        <h3 class="text-xl font-extrabold text-gray-900 text-center line-clamp-2">{{ $rankings[0]->siswa->nama_siswa }}</h3>
                        <p class="text-sm text-gray-700 mt-1">{{ $rankings[0]->nisn }}</p>
                        <div class="mt-3 bg-yellow-300 rounded-lg px-6 py-3 w-full text-center">
                            <p class="text-3xl font-extrabold text-gray-900">{{ $rankings[0]->total_poin }}</p>
                            <p class="text-xs text-gray-700 font-semibold">poin</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- 3rd Place -->
            @if(isset($rankings[2]))
            <div class="flex flex-col items-center justify-end" style="flex: 0 0 280px;">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl p-6 w-full transform hover:scale-105 transition-transform duration-200">
                    <div class="flex flex-col items-center">
                        <div class="relative mb-3">
                            <img src="{{ $rankings[2]->siswa->foto ? asset('storage/uploads/siswa/' . $rankings[2]->siswa->foto) : 'https://ui-avatars.com/api/?name=' . urlencode($rankings[2]->siswa->nama_siswa ?? 'Unknown') . '&background=cd7f32&color=ffffff&size=96' }}" 
                                 alt="{{ $rankings[2]->siswa->nama_siswa }}"
                                 class="w-20 h-20 rounded-full border-4 border-amber-600 dark:border-amber-700 object-cover"
                                 onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($rankings[2]->siswa->nama_siswa ?? 'Unknown') }}&background=cd7f32&color=ffffff&size=96'">
                            <div class="absolute -top-2 -right-2 bg-amber-600 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold text-sm shadow-md">
                                3
                            </div>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white text-center line-clamp-2">{{ $rankings[2]->siswa->nama_siswa }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $rankings[2]->nisn }}</p>
                        <div class="mt-3 bg-amber-100 dark:bg-amber-900/30 rounded-lg px-4 py-2 w-full text-center">
                            <p class="text-2xl font-bold text-amber-700 dark:text-amber-300">{{ $rankings[2]->total_poin }}</p>
                            <p class="text-xs text-amber-600 dark:text-amber-400">poin</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
        
        
    </div>
    @endif

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
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($rankings->avg('total_poin'), 1) }}</p>
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
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($rankings->avg('total_waktu_respon'), 1) }}s</p>
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