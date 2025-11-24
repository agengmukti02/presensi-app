<!DOCTYPE html>
<html>
<head>
    <title>Laporan Presensi</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 4px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Presensi Bulan {{ $month }} Tahun {{ $year }}</h2>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>NIP</th>
                @foreach($days as $day)
                    <th>{{ $day }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
                <tr>
                    <td>{{ $row['no'] }}</td>
                    <td style="text-align: left;">{{ $row['name'] }}</td>
                    <td>{{ $row['nip'] }}</td>
                    @foreach($days as $day)
                        @php $key = 'd'.$day; @endphp
                        <td>{{ $row[$key] ?? '-' }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
