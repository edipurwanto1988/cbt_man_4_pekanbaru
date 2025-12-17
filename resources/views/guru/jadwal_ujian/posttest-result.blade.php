@extends('layouts.guru')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="mb-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Review Jawaban Siswa</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $bankSoal->nama_bank }}</p>
                    </div>
                    <a href="{{ route('guru.jadwal_ujian.posttest.live', $bankSoal->id) }}" 
                       class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors">
                        <i class="ri-arrow-left-line mr-2"></i>Kembali
                    </a>
                </div>
            </div>

            <!-- Student Information Card -->
            <div class="mb-6 p-6 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900/50">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <div class="text-sm font-medium text-gray-600 dark:text-gray-400">Nama Siswa</div>
                        <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ $siswa->nama_siswa }}</div>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-600 dark:text-gray-400">NISN</div>
                        <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ $nisn }}</div>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-600 dark:text-gray-400">Waktu Mulai</div>
                        <div class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $participant->start_time ? \Carbon\Carbon::parse($participant->start_time)->format('d/m/Y H:i') : '-' }}
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <div class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Soal</div>
                        <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $totalQuestions }}</div>
                    </div>
                    
                    <div>
                        <div class="text-sm font-medium text-gray-600 dark:text-gray-400">Nilai Akhir</div>
                        <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ number_format($hasil->nilai_akhir ?? 0, 2) }}</div>
                    </div>
                </div>
            </div>

            <!-- Filter Options -->
            <div class="mb-6 p-4 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900/50">
                <div class="flex flex-wrap gap-2">
                    <button onclick="filterQuestions('all')" 
                            class="filter-btn active px-4 py-2 rounded-lg text-sm font-medium transition-colors"
                            data-filter="all">
                        Semua Soal ({{ $totalQuestions }})
                    </button>
                    <button onclick="filterQuestions('answered')" 
                            class="filter-btn px-4 py-2 rounded-lg text-sm font-medium transition-colors"
                            data-filter="answered">
                        Terjawab ({{ $answeredQuestions }})
                    </button>
                    <button onclick="filterQuestions('unanswered')" 
                            class="filter-btn px-4 py-2 rounded-lg text-sm font-medium transition-colors"
                            data-filter="unanswered">
                        Tidak Dijawab ({{ $unansweredQuestions }})
                    </button>
                </div>
            </div>

            <!-- Questions Review -->
            <div class="space-y-6">
                @foreach($questions as $index => $question)
                    @php
                        // Find student's answer by matching pertanyaan_id
                        $studentAnswer = $answers->where('pertanyaan_id', $question->id)->first();
                        
                        // Check if question is answered (either pilihan or esai)
                        $isAnswered = $studentAnswer && ($studentAnswer->jawaban_pilihan || $studentAnswer->jawaban_esai);
                        
                        $statusClass = !$isAnswered ? 'unanswered' : 'answered';
                    @endphp

                    <div class="question-item p-6 rounded-xl border-2 bg-white dark:bg-gray-900/50 {{ 
                        !$isAnswered ? 'border-gray-300 dark:border-gray-600' : 'border-blue-500 dark:border-blue-600'
                    }}" data-status="{{ $statusClass }}">
                        
                        <!-- Question Header -->
                        <div class="flex justify-between items-start mb-4">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                                Soal {{ $index + 1 }}
                            </h4>
                            <div class="flex items-center gap-2">
                                @if(!$isAnswered)
                                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                        <i class="ri-question-line mr-1"></i>Tidak Dijawab
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                        <i class="ri-check-line mr-1"></i>Terjawab
                                    </span>
                                @endif
                                
                                @if($studentAnswer && $studentAnswer->durasi_detik)
                                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300">
                                        <i class="ri-time-line mr-1"></i>{{ gmdate('i:s', $studentAnswer->durasi_detik) }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Question Content -->
                        <div class="mb-6 p-4 rounded-lg bg-gray-50 dark:bg-gray-800/50">
                            <div class="prose prose-sm max-w-none text-gray-700 dark:text-gray-300">
                                {!! $question->pertanyaan !!}
                            </div>
                        </div>

                        @if($question->jawabanSoals->isNotEmpty())
                            <!-- Multiple Choice Question -->
                            <div class="mb-4">
                                <h5 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Pilihan Jawaban:</h5>
                                <div class="space-y-3">
                                    @foreach($question->jawabanSoals as $answer)
                                        @php
                                            $isStudentAnswer = $studentAnswer && $studentAnswer->jawaban_pilihan === $answer->opsi;
                                            $isCorrectOption = $answer->is_correct;
                                        @endphp

                                        <div class="flex items-start p-4 rounded-lg border-2 transition-colors {{ 
                                            $isCorrectOption ? 'border-green-500 bg-green-50 dark:bg-green-900/20 dark:border-green-600' : 
                                            ($isStudentAnswer && !$isCorrectOption ? 'border-red-500 bg-red-50 dark:bg-red-900/20 dark:border-red-600' : 
                                            'border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800/50')
                                        }}">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span class="font-bold text-lg {{ 
                                                        $isCorrectOption ? 'text-green-700 dark:text-green-300' : 
                                                        ($isStudentAnswer ? 'text-red-700 dark:text-red-300' : 'text-gray-700 dark:text-gray-400')
                                                    }}">{{ $answer->opsi }}.</span>
                                                    
                                                    @if($isStudentAnswer)
                                                        <span class="px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                                            Jawaban Siswa
                                                        </span>
                                                    @endif
                                                    
                                                    @if($isCorrectOption)
                                                        <span class="px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                                            <i class="ri-check-double-line mr-1"></i>Kunci Jawaban
                                                        </span>
                                                    @endif
                                                </div>
                                                <span class="text-gray-800 dark:text-gray-200">{{ $answer->isi_jawaban }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Answer Summary for Multiple Choice -->
                                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <span class="font-medium text-gray-600 dark:text-gray-400">Jawaban Siswa:</span>
                                            <span class="ml-2 font-semibold {{ 
                                                !$isAnswered ? 'text-gray-500 dark:text-gray-400' : 'text-blue-600 dark:text-blue-400'
                                            }}">
                                                {{ $studentAnswer && $studentAnswer->jawaban_pilihan ? $studentAnswer->jawaban_pilihan : 'Tidak dijawab' }}
                                            </span>
                                        </div>
                                        <div>
                                            @php
                                                $correctAnswer = $question->jawabanSoals->where('is_correct', 1)->first();
                                            @endphp
                                            <span class="font-medium text-gray-600 dark:text-gray-400">Kunci Jawaban:</span>
                                            <span class="ml-2 font-semibold text-green-600 dark:text-green-400">
                                                {{ optional($correctAnswer)->opsi ?? '-' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Essay Question -->
                            <div class="mb-4">
                                <h5 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Jawaban Esai:</h5>
                                
                                @if($studentAnswer && $studentAnswer->jawaban_esai)
                                    <div class="p-4 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800">
                                        <div class="text-gray-800 dark:text-gray-200 whitespace-pre-wrap">{{ $studentAnswer->jawaban_esai }}</div>
                                    </div>
                                    
                                    <!-- Scoring Section for Essay -->
                                    <div class="mt-4 p-4 rounded-lg bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Skor:</span>
                                                <span class="ml-2 text-lg font-bold text-yellow-600 dark:text-yellow-400" id="score-display-{{ $question->id }}">
                                                    {{ $studentAnswer->skor ?? 0 }}
                                                </span>
                                            </div>
                                            <button onclick="openScoreModal({{ $question->id }}, '{{ $nisn }}', {{ $bankSoal->id }}, {{ $studentAnswer->skor ?? 0 }})" 
                                                    class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg text-sm transition-colors">
                                                <i class="ri-edit-line mr-1"></i>Edit Skor
                                            </button>
                                        </div>
                                    </div>
                                @else
                                    <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700">
                                        <p class="text-gray-500 dark:text-gray-400 italic">Siswa belum menjawab soal esai ini</p>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Print Button -->
            <div class="mt-6 flex justify-center gap-4">
                <button onclick="window.print()" 
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors font-medium">
                    <i class="ri-printer-line mr-2"></i>Cetak Review
                </button>
                <button onclick="exportToPDF()" 
                        class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors font-medium">
                    <i class="ri-file-pdf-line mr-2"></i>Export PDF
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Score Modal -->
<div id="scoreModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-xl max-w-md w-full mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Edit Skor</h3>
            <button onclick="closeScoreModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                <i class="ri-close-line text-2xl"></i>
            </button>
        </div>
        
        <form id="scoreForm" onsubmit="submitScore(event)">
            <input type="hidden" id="modalPertanyaanId">
            <input type="hidden" id="modalNisn">
            <input type="hidden" id="modalBankSoalId">
            
            <div class="mb-4">
                <label for="scoreInput" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Masukkan Skor
                </label>
                <input type="number" 
                       id="scoreInput" 
                       min="0" 
                       step="0.01"
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-yellow-500 dark:bg-gray-700 dark:text-white"
                       placeholder="Contoh: 85"
                       required>
            </div>
            
            <div class="flex gap-3">
                <button type="button" 
                        onclick="closeScoreModal()"
                        class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    Batal
                </button>
                <button type="submit"
                        class="flex-1 px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg transition-colors">
                    Simpan Skor
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .filter-btn {
        background-color: #e5e7eb;
        color: #374151;
    }
    
    .dark .filter-btn {
        background-color: #374151;
        color: #d1d5db;
    }
    
    .filter-btn.active {
        background-color: #3b82f6;
        color: white;
    }
    
    .filter-btn:hover {
        background-color: #60a5fa;
        color: white;
    }

    @media print {
        .filter-btn, button[onclick="window.print()"], button[onclick="exportToPDF()"], 
        a[href*="kembali"] {
            display: none !important;
        }
        
        .question-item {
            page-break-inside: avoid;
            break-inside: avoid;
        }
    }
</style>

<script>
    function filterQuestions(status) {
        const questions = document.querySelectorAll('.question-item');
        const buttons = document.querySelectorAll('.filter-btn');
        
        // Update active button
        buttons.forEach(btn => {
            btn.classList.remove('active');
            if (btn.dataset.filter === status) {
                btn.classList.add('active');
            }
        });
        
        // Filter questions
        questions.forEach(question => {
            if (status === 'all') {
                question.style.display = 'block';
            } else {
                const questionStatus = question.dataset.status;
                question.style.display = questionStatus === status ? 'block' : 'none';
            }
        });
    }

    function exportToPDF() {
        // This would require a backend endpoint to generate PDF
        alert('Fitur export PDF akan segera tersedia. Untuk sementara gunakan tombol Cetak.');
        window.print();
    }

    // Score Modal Functions
    function openScoreModal(pertanyaanId, nisn, bankSoalId, currentScore) {
        document.getElementById('modalPertanyaanId').value = pertanyaanId;
        document.getElementById('modalNisn').value = nisn;
        document.getElementById('modalBankSoalId').value = bankSoalId;
        document.getElementById('scoreInput').value = currentScore;
        document.getElementById('scoreModal').classList.remove('hidden');
    }

    function closeScoreModal() {
        document.getElementById('scoreModal').classList.add('hidden');
        document.getElementById('scoreForm').reset();
    }

    function submitScore(event) {
        event.preventDefault();
        
        const pertanyaanId = document.getElementById('modalPertanyaanId').value;
        const nisn = document.getElementById('modalNisn').value;
        const bankSoalId = document.getElementById('modalBankSoalId').value;
        const point = document.getElementById('scoreInput').value;
        
        // Show loading state
        const submitBtn = event.target.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="ri-loader-4-line animate-spin mr-2"></i>Menyimpan...';
        
        // Send AJAX request
        fetch('{{ route("guru.posttest.givePoint") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                nisn: nisn,
                bankSoalId: parseInt(bankSoalId),
                pertanyaanId: parseInt(pertanyaanId),
                point: parseFloat(point)
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update score display
                const scoreDisplay = document.getElementById('score-display-' + pertanyaanId);
                if (scoreDisplay) {
                    scoreDisplay.textContent = data.data.question_score;
                }
                
                // Show success notification
                showNotification('Skor berhasil disimpan!', 'success');
                
                // Close modal
                closeScoreModal();
                
                // Optionally reload page to update total score
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showNotification(data.message || 'Gagal menyimpan skor', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Terjadi kesalahan saat menyimpan skor', 'error');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    }

    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ${
            type === 'success' 
                ? 'bg-green-500 text-white' 
                : 'bg-red-500 text-white'
        }`;
        notification.innerHTML = `
            <div class="flex items-center gap-2">
                <i class="ri-${type === 'success' ? 'check' : 'close'}-circle-line text-xl"></i>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transition = 'opacity 0.3s';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    // Close modal when clicking outside
    document.getElementById('scoreModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeScoreModal();
        }
    });
</script>
@endsection