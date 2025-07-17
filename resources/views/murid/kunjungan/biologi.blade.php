@extends('layouts.guest')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h3 class="text-xl font-bold mb-4">Data Kunjungan Laboratorium Biologi</h3>

    @if (session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <table class="min-w-full bg-white shadow-md rounded border">
        <thead>
            <tr class="bg-gray-100">
                <th class="p-2 border">Nama</th>
                <th class="p-2 border">Kelas</th>
                <th class="p-2 border">Tanggal</th>
                <th class="p-2 border">Judul Materi</th>
                <th class="p-2 border">Mata Pelajaran</th>
                <th class="p-2 border">Status</th>
                <th class="p-2 border">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kunjunganBiologi as $kunjungan)
                <tr>
                    <td class="p-2 border">{{ $kunjungan->nama }}</td>
                    <td class="p-2 border">{{ $kunjungan->kelas }}</td>
                    <td class="p-2 border">{{ $kunjungan->tanggal }}</td>
                    <td class="p-2 border">{{ $kunjungan->judul_materi }}</td>
                    <td class="p-2 border">{{ $kunjungan->mata_pelajaran }}</td>
                    <td class="p-2 border">
                        @if ($kunjungan->status_verifikasi)
                            <span class="text-green-600 font-semibold">Terverifikasi</span>
                        @else
                            <span class="text-yellow-600 font-semibold">Belum</span>
                        @endif
                    </td>
                    <td class="p-2 border">
                        @if (!$kunjungan->status_verifikasi)
                            <form method="POST" action="{{ route('biologi.verifikasi', $kunjungan->id) }}">
                                @csrf
                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white py-1 px-2 rounded text-sm">Verifikasi</button>
                            </form>
                        @else
                            <span class="text-gray-500 text-sm">Sudah</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
