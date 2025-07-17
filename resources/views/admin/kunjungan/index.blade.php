@extends('layouts.admin')

@section('content')
<a href="{{ route('admin.dashboard') }}" 
   class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg shadow transition mb-4">
    {{-- Ikon Dashboard --}}
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 12l9-9 9 9M4 10v10a1 1 0 001 1h6m8-11v10a1 1 0 01-1 1h-6" />
    </svg>
</a>

@php
    $tab = request('tab', 'murid');
@endphp

<div class="bg-[#FDF6EC] p-6 md:p-8 rounded-xl shadow-md border border-[#E4DCCF]">
    <h1 class="text-3xl font-semibold text-[#0c0906] mb-6">📋 Manajemen Data Kunjungan</h1>

    {{-- Tabs --}}
    <div class="flex gap-4 mb-6">
        <a href="{{ route('admin.kunjungan.index', ['tab' => 'murid']) }}"
           class="px-4 py-2 rounded-full transition text-sm font-medium
           {{ $tab === 'murid'
                ? 'bg-[#664343] text-white shadow'
                : 'bg-white text-[#0c0a07] border border-[#E4DCCF] hover:bg-[#E4DCCF]' }}">
            👨‍🎓 Kunjungan Murid
        </a>
        <a href="{{ route('admin.kunjungan.index', ['tab' => 'guru']) }}"
           class="px-4 py-2 rounded-full transition text-sm font-medium
           {{ $tab === 'guru'
                ? 'bg-[#664343] text-white shadow'
                : 'bg-white text-[#0c0a07] border border-[#E4DCCF] hover:bg-[#E4DCCF]' }}">
            👨‍🏫 Kunjungan Guru
        </a>
    </div>

    {{-- Filter --}}
    <form method="GET" class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
        <input type="hidden" name="tab" value="{{ $tab }}">

        {{-- Dropdown periode --}}
<div x-data="{
    periode: '{{ request('periode', 'harian') }}',
    tanggal: '{{ request('tanggal') }}'
}" x-init="$watch('periode', value => updateField(value))" x-effect="updateField(periode)">
    <label class="block text-sm text-[#0c0a07] mb-1">Periode</label>
    <select name="periode" x-model="periode"
            class="w-full border border-[#E4DCCF] bg-[#FFF8F1] rounded px-3 py-2 text-[#0f0a08]">
        <option value="harian">Harian</option>
        <option value="mingguan">Mingguan</option>
        <option value="bulanan">Bulanan</option>
    </select>

    {{-- Input tanggal/format sesuai periode --}}
    <template x-if="periode === 'harian'">
        <input type="date" name="tanggal" x-model="tanggal"
            class="mt-2 w-full border border-[#E4DCCF] bg-[#FFF8F1] rounded px-3 py-2" />
    </template>
    <template x-if="periode === 'mingguan'">
        <input type="week" name="tanggal" x-model="tanggal"
            class="mt-2 w-full border border-[#E4DCCF] bg-[#FFF8F1] rounded px-3 py-2" />
    </template>
    <template x-if="periode === 'bulanan'">
        <input type="month" name="tanggal" x-model="tanggal"
            class="mt-2 w-full border border-[#E4DCCF] bg-[#FFF8F1] rounded px-3 py-2" />
    </template>
</div>

<script>
function updateField(periode) {
    // no-op, just for Alpine x-effect trigger
}
</script>


        <div>
            <label class="block text-sm text-[#0c0a07] mb-1">Lokasi</label>
            <select name="lokasi"
                    class="w-full border border-[#E4DCCF] bg-[#FFF8F1] rounded px-3 py-2 text-[#0f0b08]">
                <option value="">-- Pilih Lokasi --</option>
                <option value="biologi" {{ request('lokasi') == 'biologi' ? 'selected' : '' }}>Lab Biologi</option>
                <option value="fisika" {{ request('lokasi') == 'fisika' ? 'selected' : '' }}>Lab Fisika</option>
                <option value="kimia" {{ request('lokasi') == 'kimia' ? 'selected' : '' }}>Lab Kimia</option>
                <option value="perpustakaan" {{ request('lokasi') == 'perpustakaan' ? 'selected' : '' }}>Perpustakaan</option>
            </select>
        </div>

        <div x-data="{ lokasi: '{{ request('lokasi') }}' }" x-init="$watch('lokasi', value => tipeShow = (value === 'perpustakaan'))" x-effect="tipeShow = (lokasi === 'perpustakaan')">
            <label class="block text-sm text-[#0c0a07] mb-1">Tipe (khusus Perpustakaan)</label>
            <select name="tipe" x-show="lokasi === 'perpustakaan'"
                    class="w-full border border-[#E4DCCF] bg-[#FFF8F1] rounded px-3 py-2 text-[#0f0b08]">
                <option value="">-- Semua Tipe --</option>
                <option value="baca" {{ request('tipe') == 'baca' ? 'selected' : '' }}>Baca</option>
                <option value="pinjam" {{ request('tipe') == 'pinjam' ? 'selected' : '' }}>Pinjam</option>
            </select>
        </div>

        <div>
            <label class="block text-sm text-[#0c0a07] mb-1">Status</label>
            <select name="status"
                    class="w-full border border-[#E4DCCF] bg-[#FFF8F1] rounded px-3 py-2 text-[#0f0a08]">
                <option value="">-- Pilih Status --</option>
                <option value="Terverifikasi" {{ request('status') == 'Terverifikasi' ? 'selected' : '' }}>Terverifikasi</option>
                <option value="Menunggu" {{ request('status') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
            </select>
        </div>


        <div class="flex gap-2">
            <button type="submit"
                    class="flex-1 bg-[#795757] text-white px-4 py-2 rounded hover:bg-[#947861] transition">
                Filter
            </button>
            <a href="{{ route('admin.kunjungan.cetak', request()->query()) }}"
               target="_blank"
               class="bg-[#4F6F52] hover:bg-[#B6C7AA] text-white px-4 py-2 rounded transition">
                Cetak PDF
            </a>
        </div>
    </form>

    {{-- TABEL DAN PAGINATION --}}
<div class="bg-white p-4 rounded-lg shadow overflow-visible">
    {{-- Tabel --}}
    <div class="w-full overflow-x-auto">
        <table class="min-w-full text-sm text-left text-[#0a0705]">
            <thead class="bg-[#B6A28E] uppercase text-xs font-semibold">
                <tr>
                    <th class="px-4 py-3">No</th>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Kelas</th>
                    <th class="px-4 py-3">Lokasi</th>
                    <th class="px-4 py-3">Tanggal</th>
                    <th class="px-4 py-3">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kunjungan as $item)
                    <tr class="border-b hover:bg-[#F5EFE6] transition">
                        <td class="px-4 py-2">
                            {{ ($kunjungan->currentPage() - 1) * $kunjungan->perPage() + $loop->iteration }}
                        </td>
                        <td class="px-4 py-2">{{ $item->nama }}</td>
                        <td class="px-4 py-2">{{ $item->kelas ?? '-' }}</td>
                        <td class="px-4 py-2 capitalize">{{ $item->lokasi }}</td>
                        <td class="px-4 py-2">{{ $item->tanggal }}</td>
                        <td class="px-4 py-2">
                            <span class="inline-block text-xs font-semibold px-3 py-1 rounded-full
                                {{ $item->verifikasi_petugas 
                                    ? 'bg-green-200 text-green-800' 
                                    : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $item->verifikasi_petugas ? 'Terverifikasi' : 'Menunggu' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-gray-500">Tidak ada data kunjungan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if ($kunjungan->hasPages())
    <div class="mt-6 flex flex-wrap justify-center gap-2 text-sm">
        {{-- Previous Page Link --}}
        @if ($kunjungan->onFirstPage())
            <span class="px-3 py-1 bg-gray-300 text-gray-500 rounded cursor-not-allowed">←</span>
        @else
            <a href="{{ $kunjungan->previousPageUrl() }}" class="px-3 py-1 bg-[#C7C8CC] text-black rounded hover:bg-[#b0b0b0]">←</a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($kunjungan->getUrlRange(1, $kunjungan->lastPage()) as $page => $url)
            @if ($page == $kunjungan->currentPage())
                <span class="px-3 py-1 bg-[#7EACB5] text-white rounded font-semibold">{{ $page }}</span>
            @else
                <a href="{{ $url }}" class="px-3 py-1 bg-[#C7C8CC] text-black rounded hover:bg-[#b0b0b0]">{{ $page }}</a>
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($kunjungan->hasMorePages())
            <a href="{{ $kunjungan->nextPageUrl() }}" class="px-3 py-1 bg-[#C7C8CC] text-black rounded hover:bg-[#b0b0b0]">→</a>
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

@endsection
