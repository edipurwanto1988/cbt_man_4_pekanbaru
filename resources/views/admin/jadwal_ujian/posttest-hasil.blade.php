@extends('layouts.admin')

@section('title', 'Hasil Posttest - ' . $bankSoal->nama_bank)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Hasil Posttest: {{ $bankSoal->nama_bank }}</h1>
            <p class="text-gray-600 dark:text-gray-400">Kode: {{ $bankSoal->kode_bank }}</p>
        </div>
        <a href="{{ route('admin.jadwal_ujian.index') }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
            <i class="ri-arrow-left-line mr-2"></i>Kembali
        </a>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Peserta</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $results->count() }}</p>
                </div>
                <div class="bg-blue-100 dark:bg-blue-900/30 p-3 rounded-full">
                    <i class="ri-group-line text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Sudah Selesai</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $results->where('status', 'finished')->count() }}</p>
                </div>
                <div class="bg-green-100 dark:bg-green-900/30 p-3 rounded-full">
                    <i class="ri-checkbox-circle-line text-green-600 dark:text-green-400 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Rata-rata Nilai</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($results->avg('nilai_akhir'), 2) }}</p>
                </div>
                <div class="bg-yellow-100 dark:bg-yellow-900/30 p-3 rounded-full">
                    <i class="ri-bar-chart-line text-yellow-600 dark:text-yellow-400 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Nilai Tertinggi</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $results->max('nilai_akhir') ?? 0 }}</p>
                </div>
                <div class="bg-purple-100 dark:bg-purple-900/30 p-3 rounded-full">
                    <i class="ri-trophy-line text-purple-600 dark:text-purple-400 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Results Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">NISN</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama Siswa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Cheat Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total Benar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total Salah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kosong</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nilai Akhir</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Waktu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($results as $index => $hasil)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 @if($hasil->cheat_status === 'blocked') bg-red-50 dark:bg-red-900/10 @endif">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $hasil->nisn }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $hasil->siswa->nama_siswa ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($hasil->status == 'finished')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        <i class="ri-checkbox-circle-fill mr-1"></i> Selesai
                                    </span>
                                @elseif($hasil->status == 'active')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        Aktif
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                        {{ ucfirst($hasil->status ?? 'N/A') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($hasil->cheat_status === 'blocked')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                        <i class="ri-error-warning-fill mr-1"></i> Blocked
                                    </span>
                                    @if($hasil->cheat_reason)
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            {{ $hasil->cheat_reason }}
                                        </div>
                                    @endif
                                @elseif($hasil->cheat_status === 'normal' || $hasil->cheat_status === null)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        Normal
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                        {{ ucfirst($hasil->cheat_status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 dark:text-green-400 font-medium">{{ $hasil->total_benar }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 dark:text-red-400 font-medium">{{ $hasil->total_salah }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $hasil->total_kosong }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-white">{{ $hasil->nilai_akhir }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $hasil->waktu_pengerjaan }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($hasil->cheat_status === 'blocked')
                                    <button onclick="unblockParticipant('{{ $hasil->nisn }}', '{{ $bankSoal->id }}')"
                                        class="inline-flex items-center px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-medium rounded-lg transition">
                                        <i class="ri-lock-unlock-line mr-1"></i> Unblock
                                    </button>
                                @else
                                    <span class="text-gray-400 dark:text-gray-500">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-4">
                                    <i class="ri-file-list-line text-4xl text-gray-300 dark:text-gray-600"></i>
                                    <p class="text-gray-500 dark:text-gray-400">Belum ada hasil posttest</p>
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

<script>
function unblockParticipant(nisn, bankSoalId) {
    if (!confirm('Apakah Anda yakin ingin unblock peserta ini?')) {
        return;
    }

    fetch(`/admin/posttest/unblock`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            nisn: nisn,
            bank_soal_id: bankSoalId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Gagal unblock peserta: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat unblock peserta');
    });
}
</script>
