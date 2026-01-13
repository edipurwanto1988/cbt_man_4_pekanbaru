@extends('layouts.guru')

@section('title', 'Bank Soal')

@section('content')
    <!-- PageHeading -->
    <header class="flex flex-wrap items-center justify-between gap-4 mb-8">
        <h1 class="text-gray-900 dark:text-white text-3xl font-bold leading-tight">Bank Soal</h1>
        <a href="{{ route('guru.bank_soal.create') }}" class="flex min-w-[84px] cursor-pointer items-center justify-center gap-2 overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-wide hover:bg-primary/90 transition-colors">
            <i class="ri-add-circle-line text-xl"></i>
            <span class="truncate">Bank Soal Baru</span>
        </a>
    </header>

    <!-- Alert Message -->
    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-700 dark:bg-green-800/30 dark:text-green-400">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <!-- Search and Filter -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 mb-6">
        <form method="GET" action="{{ route('guru.bank_soal.index') }}" class="flex gap-4 flex-wrap">
            <div class="flex-1 min-w-[200px]">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Cari kode atau nama bank soal..."
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary dark:bg-gray-700 dark:text-white">
            </div>
            <div class="w-48">
                <select name="tahun_ajaran_id"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary dark:bg-gray-700 dark:text-white">
                    <option value="">Semua Tahun Ajaran</option>
                    @foreach($tahunAjarans as $tahunAjaran)
                        <option value="{{ $tahunAjaran->id }}"
                                {{ request('tahun_ajaran_id') == $tahunAjaran->id ? 'selected' : '' }}>
                            {{ $tahunAjaran->tahun_ajaran }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="w-40">
                <select name="type_test"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary dark:bg-gray-700 dark:text-white">
                    <option value="">Semua Tipe</option>
                    <option value="pretest" {{ request('type_test') == 'pretest' ? 'selected' : '' }}>Pretest</option>
                    <option value="posttest" {{ request('type_test') == 'posttest' ? 'selected' : '' }}>Posttest</option>
                </select>
            </div>
            <div class="w-40">
                <select name="status"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary dark:bg-gray-700 dark:text-white">
                    <option value="">Semua Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
            <button type="submit"
                    class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                <i class="ri-search-line"></i>
                Cari
            </button>
        </form>
    </div>

    <!-- Table -->
    <div class="overflow-visible rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900/50 relative">
        <div>
            <table class="w-full divide-y divide-gray-200 dark:divide-gray-800">
                <thead class="bg-gray-50 dark:bg-gray-800/50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Kode Bank
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Nama Bank
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Type Test
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Tahun Ajaran
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Mata Pelajaran
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800 bg-white dark:bg-gray-900/50">
                    @forelse ($bankSoals as $bankSoal)
                        <tr>
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                {{ $bankSoal->kode_bank }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                {{ $bankSoal->nama_bank }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $bankSoal->type_test == 'pretest' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' : 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300' }}">
                                    {{ ucfirst($bankSoal->type_test) }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                {{ $bankSoal->tahunAjaran->tahun_ajaran }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                <div class="flex items-center gap-2">
                                    {{ $bankSoal->mataPelajaran->nama_mapel ?? "-" }}
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                        {{ $bankSoal->pertanyaan_soals_count }}
                                    </span>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $bankSoal->status == 'draft' ? 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300' : ($bankSoal->status == 'aktif' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300') }}">
                                    {{ ucfirst($bankSoal->status) }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" class="inline-flex items-center justify-center w-8 h-8 rounded-md text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary" id="menu-button-{{ $bankSoal->id }}" aria-expanded="false" aria-haspopup="true" onclick="toggleDropdown('dropdown-{{ $bankSoal->id }}')">
                                        <i class="ri-arrow-down-s-line"></i>
                                    </button>
                                    
                                    <div id="dropdown-{{ $bankSoal->id }}" class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 dark:divide-gray-700 focus:outline-none z-[9999]" role="menu" aria-orientation="vertical" aria-labelledby="menu-button-{{ $bankSoal->id }}" tabindex="-1">
                                        <div class="py-1" role="none">
                                            <a href="{{ route('guru.bank_soal.show', $bankSoal->id) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                                <i class="ri-eye-line mr-2"></i>
                                                Lihat Detail
                                            </a>
                                            <a href="{{ route('guru.pertanyaan_soal.create', $bankSoal->id) }}" class="flex items-center px-4 py-2 text-sm text-blue-600 dark:text-blue-400 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                                <i class="ri-add-circle-line mr-2"></i>
                                                Tambah Soal
                                            </a>
                                            <a href="{{ route('guru.bank_soal.edit', $bankSoal->id) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                                <i class="ri-edit-line mr-2"></i>
                                                Edit
                                            </a>
                                            @if($bankSoal->pertanyaanSoals->count() == 0)
                                                <form action="{{ route('guru.bank_soal.destroy', $bankSoal->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus bank soal ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700 text-left" role="menuitem">
                                                        <i class="ri-delete-bin-line mr-2"></i>
                                                        Hapus
                                                    </button>
                                                </form>
                                            @else
                                                <button type="button" disabled class="flex items-center w-full px-4 py-2 text-sm text-gray-400 dark:text-gray-500 cursor-not-allowed text-left" role="menuitem" title="Tidak dapat menghapus bank soal yang sudah memiliki pertanyaan">
                                                    <i class="ri-delete-bin-line mr-2"></i>
                                                    Hapus
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                Belum ada data bank soal
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 dark:bg-gray-900/50 dark:border-gray-800 sm:px-6">
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700 dark:text-gray-300">
                        Menampilkan <span class="font-medium">{{ $bankSoals->firstItem() }}</span> hingga <span class="font-medium">{{ $bankSoals->lastItem() }}</span> dari <span class="font-medium">{{ $bankSoals->total() }}</span> hasil
                    </p>
                </div>
                <div>
                    {{ $bankSoals->links() }}
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function toggleDropdown(id) {
            const dropdown = document.getElementById(id);
            
            // Close all dropdowns first
            document.querySelectorAll('[id^="dropdown-"]').forEach(d => {
                if (d.id !== id) {
                    d.classList.add('hidden');
                }
            });
            
            // Toggle current dropdown
            dropdown.classList.toggle('hidden');
        }
        
        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            document.querySelectorAll('[id^="dropdown-"]').forEach(d => {
                if (!d.contains(event.target) && !event.target.closest('[id^="menu-button-"]')) {
                    d.classList.add('hidden');
                }
            });
        });
    </script>
    @endpush
@endsection