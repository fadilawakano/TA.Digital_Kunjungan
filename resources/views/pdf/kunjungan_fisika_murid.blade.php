<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kunjungan Murid</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #000;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #eee;
        }
    </style>
</head>
<body>

    <h2>LAPORAN KUNJUNGAN MURID<br>LABORATORIUM FISIKA<br>SMAN 15</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Kelas</th>
                <th>Tanggal</th>
                <th>Mata Pelajaran</th>
                <th>Judul Materi</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($murid as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->user->name }}</td>
                    <td>{{ $item->kelas }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                    <td>{{ $item->mata_pelajaran ?? '-' }}</td>
                    <td>{{ $item->judul_materi ?? '-' }}</td>
                    <td>{{ $item->verifikasi_petugas ? 'Terverifikasi' : 'Menunggu' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Tidak ada data kunjungan murid.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
