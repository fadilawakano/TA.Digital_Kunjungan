<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kunjungan</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 8px; text-align: center; }
        h2, h3 { text-align: center; }
    </style>
</head>
<body>
    <h2>Laporan Kunjungan {{ ucfirst($tab) }}</h2>

    @if($lokasi === 'perpustakaan')
        <h3>
            Laporan Kunjungan Perpustakaan 
            @if($tab === 'murid')
                - {{ ucfirst($tipe ?? 'Semua') }}
            @endif
        </h3>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    @if($tab === 'murid')
                        <th>Kelas</th>
                    @endif
                    <th>Judul Buku</th>
                    <th>Jumlah Buku</th>
                    <th>Tanggal</th>

                    {{-- Guru: selalu ada kolom Tanggal Pengembalian --}}
                    {{-- Murid: hanya kalau tipe pinjam --}}
                    @if($tab === 'guru' || $tipe === 'pinjam')
                        <th>Tanggal Pengembalian</th>
                    @endif

                    {{-- Murid tanpa filter tipe: tetap tampil kolom tipe --}}
                    @if($tab === 'murid' && !$tipe)
                        <th>Tipe</th>
                    @endif

                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $data = $kunjungan;
                    if ($tab === 'murid' && $tipe) {
                        $data = $kunjungan->where('tipe', $tipe);
                    }
                @endphp

                @forelse($data as $index => $k)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $k->user->name }}</td>
                        @if($tab === 'murid')
                            <td>{{ $k->kelas }}</td>
                        @endif
                        <td>{{ $k->judul_buku }}</td>
                        <td>{{ $k->jumlah_buku }}</td>
                        <td>{{ $k->tanggal }}</td>

                        @if($tab === 'guru' || $tipe === 'pinjam')
                            <td>{{ $k->tanggal_pengembalian }}</td>
                        @endif

                        @if($tab === 'murid' && !$tipe)
                            <td>{{ ucfirst($k->tipe) }}</td>
                        @endif

                        <td>{{ $k->verifikasi_petugas ? 'Terverifikasi' : 'Menunggu' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="
                            @if($tab === 'guru')
                                8
                            @elseif($tab === 'murid' && $tipe === 'pinjam')
                                8
                            @elseif($tab === 'murid' && !$tipe)
                                8
                            @else
                                7
                            @endif
                        ">
                            Tidak ada data kunjungan perpustakaan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @else
        {{-- TABEL LAB --}}
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    @if($tab === 'murid')
                        <th>Kelas</th>
                    @endif
                    <th>Mata Pelajaran</th>
                    <th>Judul Materi</th>
                    @if($tab === 'guru')
                        <th>Alat</th>
                        <th>Jumlah Alat</th>
                    @endif
                    <th>Tanggal</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kunjungan as $index => $k)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $k->user->name }}</td>
                        @if($tab === 'murid')
                            <td>{{ $k->kelas }}</td>
                        @endif
                        <td>{{ $k->mata_pelajaran }}</td>
                        <td>{{ $k->judul_materi }}</td>
                        @if($tab === 'guru')
                            <td>{{ $k->alat }}</td>
                            <td>{{ $k->jumlah_alat }}</td>
                        @endif
                        <td>{{ $k->tanggal }}</td>
                        <td>{{ $k->verifikasi_petugas ? 'Terverifikasi' : 'Menunggu' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $tab === 'guru' ? 8 : 7 }}">
                            Tidak ada data kunjungan laboratorium.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endif
</body>
</html>
