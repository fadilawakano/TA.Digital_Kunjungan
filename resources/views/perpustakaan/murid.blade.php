@extends('layouts.perpustakaan')

@section('content')
<a href="{{ route('perpustakaan.dashboard') }}" 
   class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg shadow transition">
   
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
        bulan: '{{ request('bulan') }}',
        tipe: '{{ request('tipe') }}'
     }">

    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold mb-6">Data Aktivitas Murid di Perpustakaan</h1>

        {{-- Filter & Tombol Cetak --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 gap-4">
            <form action="{{ route('perpustakaan.filter', 'murid') }}" method="GET" class="flex flex-wrap items-center gap-2">
                <label class="text-sm font-medium">Filter:</label>

                <select name="filter_jenis" x-model="filterJenis" class="border rounded px-2 py-1">
                    <option value="harian">Harian</option>
                    <option value="mingguan">Mingguan</option>
                    <option value="bulanan">Bulanan</option>
                </select>

                <select name="tipe" x-model="tipe" class="border rounded px-2 py-1">
                    <option value="">Semua Tipe</option>
                    <option value="baca" :selected="tipe === 'baca'">Baca</option>
                    <option value="pinjam" :selected="tipe === 'pinjam'">Pinjam</option>
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
                    :href="`{{ route('perpustakaan.cetak.perpus.murid') }}?filter_jenis=${filterJenis}` +
                        (filterJenis === 'harian' ? `&tanggal=${tanggal}` : '') +
                        (filterJenis === 'mingguan' ? `&minggu=${minggu}` : '') +
                        (filterJenis === 'bulanan' ? `&bulan=${bulan}` : '') +
                        (tipe ? `&tipe=${tipe}` : '')"
                    target="_blank"
                    class="bg-green-600 text-white px-4 py-1 rounded hover:bg-green-700">
                    Cetak Kunjungan Murid
                </a>
            </template>
        </div>

        {{-- Tabel Kunjungan --}}
        <div x-show="tab === 'kunjungan'" class="bg-[#FAF1E6] rounded shadow p-4 overflow-x-auto">
            <table class="table-auto w-full mt-4">
                <thead>
                    <tr class="bg-[#F2E2B1] text-left">
                        <th class="px-4 py-2">No</th>
                        <th class="px-4 py-2">Nama</th>
                        <th class="px-4 py-2">Kelas</th>
                        <th class="px-4 py-2">Tanggal</th>
                        <th class="px-4 py-2">Judul Buku</th>
                        <th class="px-4 py-2">Jumlah Buku</th>
                        <th class="px-4 py-2">Tipe</th>
                        <th class="px-4 py-2">Tgl Pengembalian</th>
                        <th class="px-4 py-2">Verifikasi</th>
                        <th class="px-4 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kunjunganPerpustakaan as $data)
                        <tr class="border-t hover:bg-[#f7e8d3]">
                            <td class="px-4 py-2">
                            {{ ($kunjunganPerpustakaan->currentPage() - 1) * $kunjunganPerpustakaan->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-4 py-2">{{ $data->user->name ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $data->kelas }}</td>
                            <td class="px-4 py-2">{{ \Carbon\Carbon::parse($data->tanggal)->format('d-m-Y') }}</td>
                            <td class="px-4 py-2">{{ $data->judul_buku }}</td>
                            <td class="px-4 py-2">{{ $data->jumlah_buku }}</td>
                            <td class="px-4 py-2">{{ ucfirst($data->tipe) }}</td>
                            <td class="px-4 py-2">
                                {{ $data->tipe === 'pinjam' ? \Carbon\Carbon::parse($data->tanggal_pengembalian)->format('d-m-Y') : '-' }}
                            </td>
                            <td class="px-4 py-2">
                                @if ($data->status_verifikasi === 'terverifikasi')
                                    <span class="text-green-600 font-semibold">Terverifikasi</span><br>
                                    <small class="text-gray-500">oleh {{ $data->verifikasi_petugas }}</small>
                                @else
                                    <div class="flex gap-2 flex-wrap">
                                        <form action="{{ route('perpustakaan.verifikasi', $data->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="berhasil">
                                            <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                                                Berhasil
                                            </button>
                                        </form>

                                        <form action="{{ route('perpustakaan.verifikasi', $data->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="kerusakan">
                                            <button type="submit" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
                                                Ada Kerusakan
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-2">
                                <form action="{{ route('perpustakaan.kunjungan.destroy', $data->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
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
                            <td colspan="9" class="text-center py-4 text-gray-500">Tidak ada data kunjungan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            @if ($kunjunganPerpustakaan->hasPages())
    <div class="mt-6 flex flex-wrap justify-center gap-2 text-sm">
        {{-- Previous Page Link --}}
        @if ($kunjunganPerpustakaan->onFirstPage())
            <span class="px-3 py-1 bg-gray-300 text-gray-500 rounded cursor-not-allowed">←</span>
        @else
            <a href="{{ $kunjunganPerpustakaan->previousPageUrl() }}" class="px-3 py-1 bg-[#C7C8CC] text-black rounded hover:bg-[#b0b0b0]">←</a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($kunjunganPerpustakaan->getUrlRange(1, $kunjunganPerpustakaan->lastPage()) as $page => $url)
            @if ($page == $kunjunganPerpustakaan->currentPage())
                <span class="px-3 py-1 bg-[#7EACB5] text-white rounded font-semibold">{{ $page }}</span>
            @else
                <a href="{{ $url }}" class="px-3 py-1 bg-[#C7C8CC] text-black rounded hover:bg-[#b0b0b0]">{{ $page }}</a>
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($kunjunganPerpustakaan->hasMorePages())
            <a href="{{ $kunjunganPerpustakaan->nextPageUrl() }}" class="px-3 py-1 bg-[#C7C8CC] text-black rounded hover:bg-[#b0b0b0]">→</a>
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
