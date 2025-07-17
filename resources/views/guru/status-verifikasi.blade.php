@extends('layouts.guru')

@section('content')
<a href="{{ route('guru.dashboard') }}" 
   class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg shadow transition">
    {{-- Ikon Dashboard --}}
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 12l9-9 9 9M4 10v10a1 1 0 001 1h6m8-11v10a1 1 0 01-1 1h-6" />
    </svg>
</a>

@php
    $currentTab = request('tab', 'biologi');
@endphp

<div class="p-6 space-y-10">
    <h2 class="text-3xl font-semibold mb-6 text-[#141016]">Cek Status Verifikasi</h2>

    {{-- Tabs --}}
    <div class="flex space-x-3 border-b border-gray-200 mb-8">
        @foreach(['biologi' => 'Lab Biologi', 'fisika' => 'Lab Fisika', 'kimia' => 'Lab Kimia', 'perpustakaan' => 'Perpustakaan'] as $key => $label)
            <a href="{{ route('guru.status-verifikasi', ['tab' => $key]) }}"
               class="px-4 py-2 text-sm font-semibold border-b-2 {{ $currentTab === $key ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-blue-600' }}">
               {{ $label }}
            </a>
        @endforeach
    </div>

    {{-- Konten berdasarkan tab --}}
    @if(in_array($currentTab, ['biologi', 'fisika', 'kimia']))
        @php $data = $$currentTab; @endphp
        <h3 class="text-2xl font-bold mb-4 text-gray-800">Status Verifikasi {{ ucfirst($currentTab) }}</h3>
        @if($data->count())
            <div class="overflow-auto rounded-lg shadow">
                <table class="min-w-full bg-white border border-gray-300 text-sm rounded-xl">
                    <thead class="bg-blue-100 text-gray-800 uppercase text-xs">
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
                        <tr class="border-b hover:bg-blue-50 transition">
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
        @php $data = $perpustakaan; @endphp
        <h3 class="text-2xl font-bold mb-4 text-gray-800">Status Verifikasi Perpustakaan</h3>
        @if($data->count())
            <div class="overflow-auto rounded-lg shadow">
                <table class="min-w-full bg-white border border-gray-300 text-sm rounded-xl">
                    <thead class="bg-blue-100 text-gray-800 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3 text-left">Nama</th>
                            <th class="px-4 py-3 text-left">Judul Buku</th>
                            <th class="px-4 py-3 text-left">Jumlah Buku</th>
                            <th class="px-4 py-3 text-left">Tanggal</th>
                            <th class="px-4 py-3 text-left">Tanggal Pengembalian</th>
                            <th class="px-4 py-3 text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @foreach($data as $item)
                        <tr class="border-b hover:bg-blue-50 transition">
                            <td class="px-4 py-3">{{ $item->nama }}</td>
                            <td class="px-4 py-3">{{ $item->judul_buku ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $item->jumlah_buku ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $item->tanggal ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $item->tanggal_pengembalian ?? '-' }}</td>
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
    @endif
</div>
@endsection
