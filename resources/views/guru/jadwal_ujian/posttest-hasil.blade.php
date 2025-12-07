    <h2 class="text-xl font-bold mb-4">
        Hasil Posttest – {{ $bankSoal->nama_bank }}
    </h2>

    <table class="table-auto w-full border">
        <thead>
            <tr class="bg-gray-100">
                <th class="p-2 border">NISN</th>
                <th class="p-2 border">Total Benar</th>
                <th class="p-2 border">Total Salah</th>
                <th class="p-2 border">Kosong</th>
                <th class="p-2 border">Nilai</th>
                <th class="p-2 border">Waktu</th>
                <th class="p-2 border">Aksi</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($results as $hasil)
                <tr>
                    <td class="p-2 border">{{ $hasil->nisn }}</td>
                    <td class="p-2 border">{{ $hasil->total_benar }}</td>
                    <td class="p-2 border">{{ $hasil->total_salah }}</td>
                    <td class="p-2 border">{{ $hasil->total_kosong }}</td>
                    <td class="p-2 border font-bold">{{ $hasil->nilai_akhir }}</td>
                    <td class="p-2 border">{{ $hasil->waktu_pengerjaan }}</td>
                    <td class="p-2 border">
                        <a href="{{ route('guru.posttest.show', $hasil->id) }}"
                           class="text-blue-600">Detail</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

