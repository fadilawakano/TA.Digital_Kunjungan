@extends('layouts.murid')

@section('content')
 <a href="{{ route('murid.dashboard') }}" 
   class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg shadow transition">
    {{-- Ikon Dashboard --}}
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 12l9-9 9 9M4 10v10a1 1 0 001 1h6m8-11v10a1 1 0 01-1 1h-6" />
    </svg>
</a>

@php
    $currentTab = request('tab', 'biologi'); // default tab
@endphp

<div class="p-6 space-y-10">

    <h2 class="text-3xl font-semibold mb-6 text-[#141016]">Cek Status Verifikasi</h2>

    {{-- Tabs --}}
    <div class="flex space-x-3 border-b border-gray-200 mb-8">
        @foreach(['biologi' => 'Lab Biologi', 'fisika' => 'Lab Fisika', 'kimia' => 'Lab Kimia', 'perpustakaan' => 'Perpustakaan'] as $key => $label)
            <a href="{{ route('murid.status-verifikasi', ['tab' => $key]) }}"
                class="px-4 py-2 text-sm font-semibold border-b-2 {{ $currentTab === $key ? 'border-[#A459D1] text-[#A459D1]' : 'border-transparent text-gray-500 hover:text-[#A459D1]' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    {{-- Konten per tab --}}
    @if(in_array($currentTab, ['biologi', 'fisika', 'kimia']))
        @php $data = $$currentTab; @endphp
        <h3 class="text-2xl font-bold mb-4 text-[#06030c]">Status Verifikasi {{ ucfirst($currentTab) }}</h3>
        @if($data->count())
            <div class="overflow-auto rounded-lg shadow">
                <table class="min-w-full bg-[#FFF8E3] border border-gray-300 text-sm rounded-xl">
                    <thead class="bg-[#D0BFFF] text-gray-800 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3 text-left">Nama</th>
                            <th class="px-4 py-3 text-left">Kelas</th>
                            <th class="px-4 py-3 text-left">Mata Pelajaran</th>
                            <th class="px-4 py-3 text-left">Judul Materi</th>
                            <th class="px-4 py-3 text-left">Tanggal</th>
                            <th class="px-4 py-3 text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @foreach($data as $item)
                        <tr class="border-b hover:bg-[#F5EEE6] transition">
                            <td class="px-4 py-3">{{ $item->nama }}</td>
                            <td class="px-4 py-3">{{ $item->kelas }}</td>
                            <td class="px-4 py-3">{{ $item->mata_pelajaran ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $item->judul_materi ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $item->tanggal ?? '-' }}</td>
                            <td class="px-4 py-3">
                                @if($item->status_verifikasi === 'berhasil')
                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">✅ Berhasil</span>
                                @elseif($item->status_verifikasi === 'kerusakan')
                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">⚠️ Rusak</span>
                                @else
                                    <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">⏳ Belum</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 italic">Belum ada data kunjungan.</p>
        @endif

    @elseif($currentTab === 'perpustakaan')
        <h3 class="text-2xl font-bold mb-4 text-[#05020a]">Status Verifikasi Perpustakaan (Kunjungan & Peminjaman)</h3>

        {{-- Filter --}}
        <form method="GET" action="{{ route('murid.status-verifikasi.filter') }}" class="bg-white rounded-lg shadow p-5 mb-6 border border-gray-200">
            <input type="hidden" name="tab" value="perpustakaan">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="tipe" class="block text-sm font-medium text-gray-700 mb-1">Tipe Kunjungan</label>
                    <select name="tipe" id="tipe" class="block w-full p-2 border rounded-lg focus:ring-[#A459D1] focus:border-[#A459D1]">
                        <option value="">Semua</option>
                        <option value="baca" {{ request('tipe') == 'baca' ? 'selected' : '' }}>Baca</option>
                        <option value="pinjam" {{ request('tipe') == 'pinjam' ? 'selected' : '' }}>Pinjam</option>
                    </select>
                </div>
                <div>
                    <label for="waktu" class="block text-sm font-medium text-gray-700 mb-1">Waktu</label>
                    <select name="waktu" id="waktu" class="block w-full p-2 border rounded-lg focus:ring-[#A459D1] focus:border-[#A459D1]">
                        <option value="">Semua</option>
                        <option value="harian" {{ request('waktu') == 'harian' ? 'selected' : '' }}>Harian</option>
                        <option value="mingguan" {{ request('waktu') == 'mingguan' ? 'selected' : '' }}>Mingguan</option>
                        <option value="bulanan" {{ request('waktu') == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="bg-[#4B352A] hover:bg-[#8A784E] text-white font-semibold py-2 px-4 rounded-lg transition">
                        Terapkan Filter
                    </button>
                </div>
            </div>
        </form>

        @if($perpustakaan->count())
        <div class="overflow-auto rounded-lg shadow">
            <table class="min-w-full bg-[#FFF8E3] border border-gray-300 text-sm rounded-xl">
                <thead class="bg-[#FFDDAB] text-gray-800 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3 text-left">Nama</th>
                        <th class="px-4 py-3 text-left">Kelas</th>
                        <th class="px-4 py-3 text-left">Judul Buku</th>
                        <th class="px-4 py-3 text-left">Jumlah</th>
                        <th class="px-4 py-3 text-left">Tanggal Kunjungan</th>
                        <th class="px-4 py-3 text-left">Tanggal Kembali</th>
                        <th class="px-4 py-3 text-left">Tipe</th>
                        <th class="px-4 py-3 text-left">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach($perpustakaan as $item)
                    <tr class="border-b hover:bg-[#F5EEE6] transition">
                        <td class="px-4 py-3">{{ $item->nama }}</td>
                        <td class="px-4 py-3">{{ $item->kelas }}</td>
                        <td class="px-4 py-3">{{ $item->judul_buku ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $item->jumlah_buku ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $item->tanggal ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $item->tanggal_pengembalian ?? '-' }}</td>
                        <td class="px-4 py-3 capitalize">{{ $item->tipe ?? '-' }}</td>
                        <td class="px-4 py-3">
                            @if($item->status_verifikasi === 'berhasil')
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">✅ Berhasil</span>
                            @else
                                <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">⏳ Belum</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
            <p class="text-gray-500 italic">Belum ada data kunjungan atau peminjaman di perpustakaan.</p>
        @endif
    @endif
</div>
@endsection
