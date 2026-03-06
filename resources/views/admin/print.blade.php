<!DOCTYPE html>
<html>
<head>
    <title>Laporan Lingkungan SIGAP</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; }
        .kop-surat {
            text-align: center;
            border-bottom: 3px solid #198754;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .kop-surat h2 { color: #198754; margin: 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 6px; vertical-align: top; }
        th { background-color: #d1e7dd; text-align: center; color: #0f5132; }
        .foto-bukti { width: 90px; border-radius: 4px; border: 1px solid #ccc; margin-top: 5px; }
    </style>
</head>
<body>
    <div class="kop-surat">
        <h2>SISTEM INFORMASI PELAPORAN LINGKUNGAN (SIGAP)</h2>
        <p style="margin: 5px 0 0 0;">Dokumen Resmi Rekapitulasi Penanganan Laporan Kebersihan & Lingkungan Warga</p>
        <small>Dicetak pada: {{ date('d F Y, H:i') }}</small>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Pelapor & Tanggal</th>
                <th width="35%">Detail Masalah & Foto Warga</th>
                <th width="30%">Tindak Lanjut Petugas & Foto</th>
                <th width="10%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reports as $index => $item)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $item->user->name }}</strong><br>
                        <small>{{ $item->created_at->format('d/m/Y H:i') }}</small>
                    </td>
                    <td>
                        <strong>{{ $item->title }}</strong><br>
                        {{ $item->description }}<br>
                        @if ($item->location)
                            <small> {{ $item->location }}</small><br>
                        @endif
                        @if ($item->image)
                            <img src="{{ public_path('storage/' . $item->image) }}" class="foto-bukti">
                        @endif
                    </td>
                    <td>
                        @if ($item->responses->count() > 0)
                            <small><b>Update:</b> {{ $item->responses->last()->created_at->format('d/m/Y') }}</small><br>
                            {{ $item->responses->last()->response_text }}<br>
                            @if ($item->responses->last()->image)
                                <img src="{{ public_path('storage/' . $item->responses->last()->image) }}" class="foto-bukti">
                            @endif
                        @else
                            <small style="color: red;">Belum ditindaklanjuti</small>
                        @endif
                    </td>
                    <td style="text-align: center; font-weight: bold;">
                        {{ strtoupper($item->status == '0' ? 'Pending' : $item->status) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>