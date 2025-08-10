@extends('layouts.fisika')

@section('content')
  <a href="{{ route('fisika.dashboard') }}" 
   class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg shadow transition">
    {{-- Ikon Dashboard --}}
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 12l9-9 9 9M4 10v10a1 1 0 001 1h6m8-11v10a1 1 0 01-1 1h-6" />
    </svg>
</a>

<div class="p-6"
     x-data="{
        tab: 'kunjungan',
        filterJenis: '{{ request('filter_jenis', 'harian') }}',
        tanggal: '{{ request('tanggal') }}',
        minggu: '{{ request('minggu') }}',
        bulan: '{{ request('bulan') }}'
     }">

    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold mb-6">Data Kunjungan Guru ke Lab Fisika</h1>

        {{-- Filter & Tombol Cetak --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 gap-4">
            <form action="{{ route('fisika.filter', 'guru') }}" method="GET" class="flex flex-wrap items-center gap-2">
                <label class="text-sm font-medium">Filter:</label>

                <select name="filter_jenis" x-model="filterJenis" class="border rounded px-2 py-1">
                    <option value="harian">Harian</option>
                    <option value="mingguan">Mingguan</option>
                    <option value="bulanan">Bulanan</option>
                </select>

                <input type="date" name="tanggal" x-show="filterJenis === 'harian'" x-model="tanggal" class="border rounded px-2 py-1" />
                <input type="week" name="minggu" x-show="filterJenis === 'mingguan'" x-model="minggu" class="border rounded px-2 py-1" />
                <input type="month" name="bulan" x-show="filterJenis === 'bulanan'" x-model="bulan" class="border rounded px-2 py-1" />

                <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">
                    Tampilkan
                </button>
            </form>

                 <template x-if="tab === 'kunjungan'">
                <a 
                    :href="`{{ route('fisika.cetak.fisika.guru') }}?filter_jenis=${filterJenis}` +
                        (filterJenis === 'harian' ? `&tanggal=${tanggal}` : '') +
                        (filterJenis === 'mingguan' ? `&minggu=${minggu}` : '') +
                        (filterJenis === 'bulanan' ? `&bulan=${bulan}` : '')"
                    target="_blank"
                    class="bg-green-600 text-white px-4 py-1 rounded hover:bg-green-700">
                    Cetak Kunjungan Guru
                </a>
            </template>
        </div>

        <div x-show="tab === 'kunjungan'" class="bg-[#FAF1E6] rounded shadow p-4">
        <div class="overflow-x-auto overflow-y-auto max-w-full" style="max-height: 80vh;">
        <table class="min-w-full table-auto mt-4 text-sm break-words">
            <thead>
                <tr class="bg-[#F2E2B1] text-left">
                    <th class="px-4 py-2">Nama</th>
                    <th class="px-4 py-2">Kelas</th>
                    <th class="px-4 py-2">Tanggal</th>
                    <th class="px-4 py-2">Mata Pelajaran</th>
                    <th class="px-4 py-2">Alat</th>
                    <th class="px-4 py-2">Jumlah Alat</th>
                    <th class="px-4 py-2">Judul Materi</th>
                    <th class="px-4 py-2">Verifikasi</th>
                    <th class="px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $item)
                    <tr class="border-t hover:bg-[#f7e8d3]">
                        <td class="px-4 py-2">{{ $item->user->name ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $item->kelas }}</td>
                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                        <td class="px-4 py-2">{{ $item->mata_pelajaran }}</td>
                        <td class="px-4 py-2">{{ $item->alat }}</td>
                        <td class="px-4 py-2">{{ $item->jumlah_alat }}</td>
                        <td class="px-4 py-2 break-words whitespace-normal max-w-[120px] align-top">
                            {{ $item->judul_materi }}
                        </td>
                        <td class="px-4 py-2 break-words whitespace-normal max-w-[150px] align-top">
                            @if ($item->status_verifikasi === 'terverifikasi')
                                <span class="text-green-600 font-semibold">Terverifikasi</span><br>
                                <small class="text-gray-500">oleh {{ $item->verifikasi_petugas }}</small>
                            @else
                                <div class="flex flex-col gap-2">
                                    <form action="{{ route('fisika.verifikasi', $item->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="berhasil">
                                        <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 w-full">
                                            Berhasil
                                        </button>
                                    </form>

                                    <form action="{{ route('fisika.verifikasi', $item->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="kerusakan">
                                        <button type="submit" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 w-full">
                                            Ada Kerusakan
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </td>

                        <td class="px-4 py-2">
                            <form action="{{ route('fisika.kunjungan.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-gray-500">Tidak ada data.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if ($data->hasPages())
    <div class="mt-6 flex flex-wrap justify-center gap-2 text-sm">
        {{-- Previous Page Link --}}
        @if ($data->onFirstPage())
            <span class="px-3 py-1 bg-gray-300 text-gray-500 rounded cursor-not-allowed">←</span>
        @else
            <a href="{{ $data->previousPageUrl() }}" class="px-3 py-1 bg-[#C7C8CC] text-black rounded hover:bg-[#b0b0b0]">←</a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($data->getUrlRange(1, $data->lastPage()) as $page => $url)
            @if ($page == $data->currentPage())
                <span class="px-3 py-1 bg-[#7EACB5] text-white rounded font-semibold">{{ $page }}</span>
            @else
                <a href="{{ $url }}" class="px-3 py-1 bg-[#C7C8CC] text-black rounded hover:bg-[#b0b0b0]">{{ $page }}</a>
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($data->hasMorePages())
            <a href="{{ $data->nextPageUrl() }}" class="px-3 py-1 bg-[#C7C8CC] text-black rounded hover:bg-[#b0b0b0]">→</a>
        @else
            <span class="px-3 py-1 bg-gray-300 text-gray-500 rounded cursor-not-allowed">→</span>
        @endif
    </div>
@endif


<style>
    /* Pastikan tombol pagination tidak hilang */
    .pagination {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
    }

    .pagination nav {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
    }
</style>
</div>
    </div>
</div>
@endsection
