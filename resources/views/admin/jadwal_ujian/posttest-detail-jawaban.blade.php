@extends('layouts.admin')

@section('title', 'Detail Jawaban - ' . $siswa->nama_siswa)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detail Jawaban Posttest</h1>
            <p class="text-gray-600 dark:text-gray-400">{{ $bankSoal->nama_bank }} - {{ $siswa->nama_siswa }} ({{ $siswa->nisn }})</p>
        </div>
        <a href="{{ route('admin.jadwal_ujian.posttest.hasil', $bankSoal->id) }}"
            class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition-colors duration-200">
            <i class="ri-arrow-left-line mr-2"></i>Kembali ke Hasil
        </a>
    </div>

    <!-- Summary Card -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Ringkasan Hasil</h2>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Total Benar</p>
                <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $hasil->total_benar ?? 0 }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Total Salah</p>
                <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $hasil->total_salah ?? 0 }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Tidak Dijawab</p>
                <p class="text-2xl font-bold text-gray-600 dark:text-gray-400">{{ $hasil->total_kosong ?? 0 }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Nilai Akhir</p>
                <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $hasil->nilai_akhir ?? 0 }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Waktu</p>
                <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $hasil->waktu_pengerjaan ?? '-' }}</p>
            </div>
        </div>
    </div>

    <!-- Questions and Answers -->
    <div class="space-y-4">
        @foreach($questions as $index => $question)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
                <div class="bg-gray-50 dark:bg-gray-700 px-6 py-3 border-b border-gray-200 dark:border-gray-600">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Soal #{{ $index + 1 }}
                            <span class="text-sm font-normal text-gray-600 dark:text-gray-400">
                                ({{ ucfirst($question->jenis_soal) }})
                            </span>
                        </h3>
                        @if($question->log)
                            @if($question->log->is_benar == 1)
                                <span class="px-3 py-1 bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 text-sm font-semibold rounded-full">
                                    <i class="ri-check-line"></i> Benar ({{ $question->log->skor }} poin)
                                </span>
                            @elseif($question->log->is_benar == 0 && $question->log->jawaban_pilihan)
                                <span class="px-3 py-1 bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 text-sm font-semibold rounded-full">
                                    <i class="ri-close-line"></i> Salah ({{ $question->log->skor }} poin)
                                </span>
                            @else
                                <span class="px-3 py-1 bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200 text-sm font-semibold rounded-full">
                                    <i class="ri-question-line"></i> Tidak Dijawab
                                </span>
                            @endif
                        @else
                            <span class="px-3 py-1 bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200 text-sm font-semibold rounded-full">
                                <i class="ri-question-line"></i> Tidak Dijawab
                            </span>
                        @endif
                    </div>
                </div>

                <div class="p-6">
                    <!-- Question Text -->
                    <div class="mb-4">
                        <p class="text-gray-900 dark:text-white font-medium mb-2">Pertanyaan:</p>
                        <div class="prose dark:prose-invert max-w-none">
                            {!! $question->pertanyaan !!}
                        </div>
                    </div>

                    @if($question->tipe_soal === 'pilihan_ganda')
                        <!-- Multiple Choice Options -->
                        <div class="space-y-2">
                            <p class="text-gray-900 dark:text-white font-medium mb-2">Pilihan Jawaban:</p>
                            @foreach(['A', 'B', 'C', 'D', 'E'] as $option)
                                @php
                                    $optionField = 'pilihan_' . strtolower($option);
                                    $isCorrect = $question->jawaban_benar === $option;
                                    $isSelected = $question->log && $question->log->jawaban_pilihan === $option;
                                @endphp
                                
                                @if($question->$optionField)
                                    <div class="flex items-start gap-3 p-3 rounded-lg {{ $isCorrect ? 'bg-green-50 dark:bg-green-900/20 border-2 border-green-500' : ($isSelected ? 'bg-red-50 dark:bg-red-900/20 border-2 border-red-500' : 'bg-gray-50 dark:bg-gray-700') }}">
                                        <span class="font-bold {{ $isCorrect ? 'text-green-700 dark:text-green-300' : ($isSelected ? 'text-red-700 dark:text-red-300' : 'text-gray-700 dark:text-gray-300') }}">
                                            {{ $option }}.
                                        </span>
                                        <div class="flex-1">
                                            <div class="prose dark:prose-invert max-w-none">
                                                {!! $question->$optionField !!}
                                            </div>
                                        </div>
                                        @if($isCorrect)
                                            <i class="ri-check-double-line text-green-600 dark:text-green-400 text-xl"></i>
                                        @elseif($isSelected)
                                            <i class="ri-close-circle-line text-red-600 dark:text-red-400 text-xl"></i>
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        </div>

                    @elseif($question->tipe_soal === 'benar_salah')
                        <!-- True/False Options -->
                        <div class="space-y-2">
                            <p class="text-gray-900 dark:text-white font-medium mb-2">Pilihan Jawaban:</p>
                            @foreach([1 => 'Benar', 0 => 'Salah'] as $value => $label)
                                @php
                                    $isCorrect = $question->jawaban_benar_salah == $value;
                                    $isSelected = $question->log && $question->log->jawaban_benar_salah == $value;
                                @endphp
                                
                                <div class="flex items-center gap-3 p-3 rounded-lg {{ $isCorrect ? 'bg-green-50 dark:bg-green-900/20 border-2 border-green-500' : ($isSelected ? 'bg-red-50 dark:bg-red-900/20 border-2 border-red-500' : 'bg-gray-50 dark:bg-gray-700') }}">
                                    <span class="font-bold {{ $isCorrect ? 'text-green-700 dark:text-green-300' : ($isSelected ? 'text-red-700 dark:text-red-300' : 'text-gray-700 dark:text-gray-300') }}">
                                        {{ $label }}
                                    </span>
                                    @if($isCorrect)
                                        <i class="ri-check-double-line text-green-600 dark:text-green-400 text-xl ml-auto"></i>
                                    @elseif($isSelected)
                                        <i class="ri-close-circle-line text-red-600 dark:text-red-400 text-xl ml-auto"></i>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                    @elseif($question->jenis_soal === 'esai')
                        <!-- Essay Answer -->
                        <div class="space-y-3">
                            <div>
                                <p class="text-gray-900 dark:text-white font-medium mb-2">Jawaban Siswa:</p>
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    @if($question->log && $question->log->jawaban_esai)
                                        <p class="text-gray-900 dark:text-white whitespace-pre-wrap">{{ $question->log->jawaban_esai }}</p>
                                    @else
                                        <p class="text-gray-500 dark:text-gray-400 italic">Tidak ada jawaban</p>
                                    @endif
                                </div>
                            </div>
                            
                            <div>
                                <p class="text-gray-900 dark:text-white font-medium mb-2">Berikan Skor:</p>
                                <div class="flex items-center gap-3">
                                    <input 
                                        type="number" 
                                        id="score-{{ $question->id }}"
                                        value="{{ $question->log->skor ?? 0 }}"
                                        min="0"
                                        max="{{ $question->poin_soal }}"
                                        class="w-24 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500"
                                    >
                                    <span class="text-gray-600 dark:text-gray-400">/ {{ $question->poin_soal }}</span>
                                    <button 
                                        onclick="saveEssayScore({{ $question->id }}, {{ $bankSoal->id }}, '{{ $siswa->nisn }}')"
                                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200 flex items-center gap-2">
                                        <i class="ri-save-line"></i>
                                        Simpan Skor
                                    </button>
                                </div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    Skor saat ini: <span class="font-semibold">{{ $question->log->skor ?? 0 }}</span>
                                </p>
                            </div>
                        </div>
                    @endif

                    <!-- Duration -->
                    @if($question->log && $question->log->durasi_detik)
                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-600">
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                <i class="ri-time-line"></i> Waktu pengerjaan: {{ gmdate('i:s', $question->log->durasi_detik) }} menit
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
function saveEssayScore(questionId, bankSoalId, nisn) {
    const scoreInput = document.getElementById('score-' + questionId);
    const score = parseFloat(scoreInput.value);
    
    if (isNaN(score) || score < 0) {
        alert('Skor tidak valid!');
        return;
    }
    
    // Disable button during save
    const button = event.target.closest('button');
    const originalText = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<i class="ri-loader-4-line animate-spin"></i> Menyimpan...';
    
    fetch(`/admin/jadwal_ujian/posttest-save-essay-score`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            question_id: questionId,
            bank_soal_id: bankSoalId,
            nisn: nisn,
            score: score
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success notification
            showNotification('Skor berhasil disimpan!', 'success');
            // Reload page to update summary
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            showNotification(data.message || 'Gagal menyimpan skor', 'error');
            button.disabled = false;
            button.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan saat menyimpan skor', 'error');
        button.disabled = false;
        button.innerHTML = originalText;
    });
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    } text-white`;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>
@endsection
