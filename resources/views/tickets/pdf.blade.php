<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tiket Parkir - {{ $transaction->no_tiket }}</title>
    <style>
        @page {
            size: 80mm 100mm;
            margin: 0;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            width: 80mm;
            margin: 0;
            padding: 0;
            font-family: DejaVu Sans, Arial, sans-serif;
            color: #111;
            background: #fff;
        }

        .ticket {
            width: 80mm;
            padding: 14mm 8mm 8mm;
            text-align: center;
            background: #fff;
            page-break-inside: avoid;
            page-break-after: avoid;
        }

        .brand {
            font-size: 10px;
            font-weight: normal;
            margin-bottom: 4px;
            letter-spacing: .3px;
        }

        .address {
            font-size: 6.5px;
            line-height: 1.35;
            margin-bottom: 14mm;
        }

        .title {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 8px;
            letter-spacing: .4px;
        }

        .main-info {
            font-size: 9px;
            font-weight: bold;
            line-height: 1.5;
            margin-bottom: 11mm;
        }

        .detail {
            font-size: 6.5px;
            font-weight: bold;
            line-height: 1.45;
            margin-bottom: 10mm;
        }

        .warning {
            font-size: 6.5px;
            font-weight: bold;
            line-height: 1.35;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    @php
        $jenis = strtolower($vehicleType->jenis ?? '');

        if ($jenis === 'motorcycle') {
            $jenisKendaraan = 'Motor';
        } elseif ($jenis === 'car') {
            $jenisKendaraan = 'Mobil';
        } elseif ($jenis === 'other') {
            $jenisKendaraan = 'Other';
        } else {
            $jenisKendaraan = ucfirst($vehicleType->jenis ?? '-');
        }
    @endphp

    <div class="ticket">
        <div class="brand">SIJA PARKING</div>

        <div class="address">
            Jl. Raya Karadenan No. 7, Karadenan<br>
            Kec. Cibinong, Kabupaten Bogor, Jawa<br>
            Barat 16111
        </div>

        <div class="title">TIKET PARKIR</div>

        <div class="main-info">
            {{ $location->location_name ?? '-' }}<br>
            {{ $jenisKendaraan }}
        </div>

        <div class="detail">
            No Tiket : {{ $transaction->no_tiket }}<br>
            Tanggal : {{ \Carbon\Carbon::parse($transaction->masuk)->format('Y-m-d H:i:s') }}
        </div>

        <div class="warning">
            Jangan meninggalkan tiket dan barang<br>
            berharga di dalam kendaraan
        </div>
    </div>
</body>
</html>
