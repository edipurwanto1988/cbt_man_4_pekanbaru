@extends('layouts.participant')

@section('title', 'Riwayat Test')

@section('content')
<div class="space-y-8">

    <h1 class="text-2xl font-bold">Riwayat Pretest</h1>

    <div class="space-y-4">
        @forelse($pretestHistory as $item)
            <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-semibold">{{ $item->bankSoal->nama_bank }}</h2>
                    <p class="text-sm text-gray-500">Tanggal: {{ $item->created_at->format('d M Y H:i') }}</p>
                </div>
                <div class="text-right">
                    <div class="text-xl font-bold text-blue-600">{{ $item->total_score }}</div>
                    <a href="/participant/exams/results/{{ $item->session_id }}"
                       class="text-blue-500 underline text-sm">
                        Lihat Detail
                    </a>
                </div>
            </div>
        @empty
            <p class="text-gray-500">Belum ada riwayat pretest.</p>
        @endforelse
    </div>


    <h1 class="text-2xl font-bold mt-10">Riwayat Posttest</h1>

    <div class="space-y-4">
        @forelse($posttestHistory as $item)
            <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-semibold">{{ $item->bankSoal->nama_bank }}</h2>
                    <p class="text-sm text-gray-500">Tanggal: {{ $item->created_at->format('d M Y H:i') }}</p>
                </div>
                <div class="text-right">
                    <div class="text-xl font-bold text-green-600">{{ $item->total_score }}</div>
                   <a href="/participant/exams/results/{{ $item->session_id }}"
                       class="text-blue-500 underline text-sm">
                        Lihat Detail
                    </a>
                </div>
            </div>
        @empty
            <p class="text-gray-500">Belum ada riwayat posttest.</p>
        @endforelse
    </div>

</div>
@endsection
