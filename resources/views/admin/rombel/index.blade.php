@extends('layouts.admin')

@section('title', 'Data Rombel')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Data Rombel</h1>
            <p class="text-gray-600 dark:text-gray-400">Kelola data rombongan belajar</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.rombel.import') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                <i class="ri-upload-line"></i>
                Import Rombel
            </a>
            <a href="{{ route('admin.rombel.create') }}"
               class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/90 transition-colors flex items-center gap-2">
                <i class="ri-add-line"></i>
                Tambah Rombel
            </a>
        </div>
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

    <!-- Search and Filter -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4">
        <form method="GET" action="{{ route('admin.rombel.index') }}" class="flex gap-4">
            <div class="flex-1">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Cari nama rombel..." 
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary dark:bg-gray-700 dark:text-white">
            </div>
            <button type="submit" 
                    class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                <i class="ri-search-line"></i>
                Cari
            </button>
        </form>
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
                            Nama Rombel
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Tingkat
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Kelas
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Wali Kelas
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Tahun Ajaran
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($rombel as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors align-top">
                            <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $rombel->firstItem() + $loop->index }}
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0 bg-primary/10 rounded-full flex items-center justify-center">
                                        <i class="ri-group-line text-primary"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $item->nama_rombel }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $item->tingkatKelas->nama ?? '-' }}
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded-full">
                                    {{ $item->kode_kelas }}
                                </span>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $item->waliKelas->nama_guru ?? '-' }}
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $item->tahunAjaran->tahun_ajaran ?? '-' }}
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- Dropdown Menu -->
                                    <div class="relative inline-block text-left">
                                        <button type="button"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-md text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary"
                                                onclick="toggleDropdown('dropdown-{{ $item->id }}')">
                                            <i class="ri-arrow-down-s-line"></i>
                                        </button>
                                        
                                        <div id="dropdown-{{ $item->id }}"
                                             class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 dark:divide-gray-700 focus:outline-none z-[9999]">
                                            <div class="py-1">
                                                <a href="{{ route('admin.rombel.show', $item->id) }}"
                                                   class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                    <i class="ri-eye-line mr-2"></i>
                                                    Lihat Detail
                                                </a>
                                                <a href="{{ route('admin.rombel.edit', $item->id) }}"
                                                   class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                    <i class="ri-edit-line mr-2"></i>
                                                    Edit
                                                </a>
                                                <a href="{{ route('admin.rombel.siswa', $item->id) }}"
                                                   class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                    <i class="ri-graduation-cap-line mr-2"></i>
                                                    Siswa
                                                </a>
                                                <a href="{{ route('admin.rombel.mapel', $item->id) }}"
                                                   class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                    <i class="ri-book-line mr-2"></i>
                                                    Mata Pelajaran
                                                </a>
                                                @if($item->canBeDeleted())
                                                    <form action="{{ route('admin.rombel.destroy', $item->id) }}"
                                                          method="POST"
                                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus data rombel ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="flex items-center w-full px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700 text-left">
                                                            <i class="ri-delete-bin-line mr-2"></i>
                                                            Hapus
                                                        </button>
                                                    </form>
                                                @else
                                                    <button type="button"
                                                            disabled
                                                            class="flex items-center w-full px-4 py-2 text-sm text-gray-400 dark:text-gray-500 cursor-not-allowed text-left"
                                                            title="Tidak dapat menghapus rombel yang memiliki siswa atau mata pelajaran">
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
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-4">
                                    <i class="ri-group-line text-4xl text-gray-300 dark:text-gray-600"></i>
                                    <p class="text-gray-500 dark:text-gray-400">Belum ada data rombel</p>
                                    <a href="{{ route('admin.rombel.create') }}"
                                       class="text-primary hover:text-primary/80 font-medium">
                                        Tambah Rombel Pertama
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($rombel->hasPages())
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700 dark:text-gray-300">
                Menampilkan {{ $rombel->firstItem() }} hingga {{ $rombel->lastItem() }} dari {{ $rombel->total() }} data
            </div>
            {{ $rombel->links() }}
        </div>
    @endif
</div>

<script>
function toggleDropdown(dropdownId) {
    const dropdown = document.getElementById(dropdownId);
    
    // Close all other dropdowns
    document.querySelectorAll('[id^="dropdown-"]').forEach(el => {
        if (el.id !== dropdownId) {
            el.classList.add('hidden');
        }
    });
    
    // Toggle current dropdown
    dropdown.classList.toggle('hidden');
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('.relative')) {
        document.querySelectorAll('[id^="dropdown-"]').forEach(el => {
            el.classList.add('hidden');
        });
    }
});
</script>
@endsection