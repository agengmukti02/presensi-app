<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Presensi {{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        @page {
            margin: 10mm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 7px;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
        }
        .header h2 {
            font-size: 14px;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 10px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #333;
            padding: 4px 2px;
            text-align: center;
        }
        th {
            background-color: #4472C4;
            color: white;
            font-weight: bold;
            font-size: 8px;
        }
        td {
            font-size: 6px;
        }
        .col-no {
            width: 18px;
        }
        .col-nama {
            width: 90px;
            text-align: left;
            padding-left: 3px;
        }
        .col-nip {
            width: 65px;
        }
        .col-day {
            width: 18px;
        }
        .status-hadir {
            color: #008000;
            font-weight: bold;
        }
        .status-sakit {
            color: #FFA500;
            font-weight: bold;
        }
        .status-izin {
            color: #0066CC;
            font-weight: bold;
        }
        .status-dd {
            color: #8B008B;
            font-weight: bold;
        }
        .status-dl {
            color: #FF6600;
            font-weight: bold;
        }
        .footer {
            margin-top: 20px;
            font-size: 8px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN PRESENSI PEGAWAI</h2>
        <p>Bulan: {{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="col-no">No</th>
                <th class="col-nama">Nama</th>
                <th class="col-nip">NIP</th>
                @foreach($days as $day)
                    <th class="col-day">{{ $day }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $row)
                <tr>
                    <td class="col-no">{{ $row['no'] }}</td>
                    <td class="col-nama">{{ $row['name'] }}</td>
                    <td class="col-nip">{{ $row['nip'] }}</td>
                    @foreach($days as $day)
                        @php
                            $val = $row['d' . $day] ?? null;
                            $class = '';
                            $content = '-';
                            
                            if ($val) {
                                if ($val === 'sakit') {
                                    $content = 'S';
                                    $class = 'status-sakit';
                                } elseif ($val === 'izin') {
                                    $content = 'I';
                                    $class = 'status-izin';
                                } elseif ($val === 'dd') {
                                    $content = 'DD';
                                    $class = 'status-dd';
                                } elseif ($val === 'dl') {
                                    $content = 'DL';
                                    $class = 'status-dl';
                                } else {
                                    $content = $val;
                                    $class = 'status-hadir';
                                }
                            }
                        @endphp
                        <td class="col-day {{ $class }}">{{ $content }}</td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ 3 + count($days) }}">Tidak ada data presensi</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
