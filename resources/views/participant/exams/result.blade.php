<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pretest Result</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            font-family: 'Poppins', sans-serif;
        }

        .podium-card {
            transition: 0.3s;
        }

        .podium-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>

<body class="text-white p-6">

    <div class="max-w-3xl mx-auto text-center">
        
        <h1 class="text-4xl font-extrabold mb-6">🎉 Hasil Pretest</h1>
        <p class="text-xl mb-8">Peringkat Peserta</p>

        <!-- TOP 3 (Podium Style) -->
        <div class="grid grid-cols-3 gap-4 mb-16 text-black">

            <!-- 2nd Place -->
            @if(isset($results[1]))
            <div class="podium-card bg-yellow-200 rounded-xl p-4 shadow-xl">
                 <div class="bg-blue-800 border-2 border-white rounded-full h-20 w-20 flex items-center justify-center">
                    <img src="https://api.dicebear.com/7.x/big-ears/svg?seed={{ urlencode($results[1]->siswa->nama_siswa ?? 'Unknown') }}&background=6366f1&color=ffffff&size=32"
                        alt="{{ $results[1]->siswa->nama_siswa }}"
                        class="w-14 h-14 rounded-full "
                        onerror="this.src='https://api.dicebear.com/7.x/big-ears/svg?seed={{ urlencode($results[1]->siswa->nama_siswa ?? 'Unknown') }}&background=6366f1&color=ffffff&size=32'">
                </div>
                <div class="text-xl font-semibold mt-2">{{ $results[1]->nisn }}</div>
                <div class="text-xl font-semibold mt-2">{{ $results[1]->siswa->nama_siswa }}</div>
                <div class="text-lg">Score: {{ $results[1]->total_poin }}</div>
            </div>
            @else
            <div></div>
            @endif

            <!-- 1st Place -->
            @if(isset($results[0]))
            <div class="podium-card bg-yellow-300 rounded-xl p-6 shadow-xl scale-110 flex justify-center flex-col items-center">
                <div class="bg-blue-800 border-2 border-white rounded-full h-20 w-20 flex items-center justify-center">
                    <img src="https://api.dicebear.com/7.x/big-ears/svg?seed={{ urlencode($results[0]->siswa->nama_siswa ?? 'Unknown') }}&background=6366f1&color=ffffff&size=32"
                        alt="{{ $results[0]->siswa->nama_siswa }}"
                        class="w-14 h-14 rounded-full "
                        onerror="this.src='https://api.dicebear.com/7.x/big-ears/svg?seed={{ urlencode($results[0]->siswa->nama_siswa ?? 'Unknown') }}&background=6366f1&color=ffffff&size=32'">
                </div>
                
                <div class="text-2xl font-extrabold mt-2">{{ $results[0]->nisn }}</div>
                <div class="text-xl font-semibold mt-2">{{ $results[0]->siswa->nama_siswa }}</div>
                <div class="text-xl font-semibold mt-1">Score: {{ $results[0]->total_poin }}</div>
            </div>
            @endif

            <!-- 3rd Place -->
            @if(isset($results[2]))
            <div class="podium-card bg-yellow-500 rounded-xl p-4 shadow-xl">
                <div class="bg-blue-800 border-2 border-white rounded-full h-20 w-20 flex items-center justify-center">
                    <img src="https://api.dicebear.com/7.x/big-ears/svg?seed={{ urlencode($results[2]->siswa->nama_siswa ?? 'Unknown') }}&background=6366f1&color=ffffff&size=32"
                        alt="{{ $results[2]->siswa->nama_siswa }}"
                        class="w-14 h-14 rounded-full "
                        onerror="this.src='https://api.dicebear.com/7.x/big-ears/svg?seed={{ urlencode($results[2]->siswa->nama_siswa ?? 'Unknown') }}&background=6366f1&color=ffffff&size=32'">
                </div>
                <div class="text-xl font-semibold mt-2">{{ $results[2]->nisn }}</div>
                <div class="text-xl font-semibold mt-2">{{ $results[2]->siswa->nama_siswa }}</div>
                <div class="text-lg">Score: {{ $results[2]->total_poin }}</div>
            </div>
            @else
            <div></div>
            @endif

        </div>

        <!-- TABLE RANKING -->
        <div class="bg-white rounded-xl shadow-2xl p-6 text-black">

            <h2 class="text-2xl font-bold mb-4 text-purple-700">
                📊 Ranking Lengkap
            </h2>

            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b">
                        <th class="py-3">Rank</th>
                        <th>NISN</th>
                        <th>Score</th>
                        <th>Benar</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($results as $index => $r)
                    <tr class="border-b hover:bg-gray-100 transition">
                        <td class="py-3 font-bold text-purple-600">{{ $index + 1 }}</td>
                        <td>{{ $r->nisn }}</td>
                        <td>{{ $r->total_poin }}</td>
                        <td>{{ $r->total_benar }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>

    </div>

</body>
</html>
