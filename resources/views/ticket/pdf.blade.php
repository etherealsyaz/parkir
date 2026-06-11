<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px;
               text-align: center; padding: 20px; }
        h2 { font-size: 16px; font-weight: bold; margin: 10px 0; }
        h3 { font-size: 14px; font-weight: bold; margin: 5px 0; }
        .divider { border-top: 1px dashed #999; margin: 10px 0; }
        .warning { font-size: 10px; font-weight: bold; margin-top: 15px; }
    </style>
</head>
<body>
    <p>SIJA PARKING<br>
    Jl. Raya Karadenan No 7, Karadenan,<br>
    Kec. Cibinong, Kabupaten Bogor, Jawa Barat 16111</p>
    <div class="divider"></div>
    <h2>TIKET PARKIR</h2>
    <h3>{{ $trx->location->location_name }}</h3>
    <h3>{{ ucfirst($trx->vehicleType->jenis) }}</h3>
    <div class="divider"></div>
    <p>
        <strong>No Tiket : {{ $trx->no_tiket }}</strong><br>
        Tanggal : {{ $trx->masuk }}
    </p>
    <div class="divider"></div>
    <p class="warning">JANGAN MENINGGALKAN TIKET DAN BARANG<br>BERHARGA DI DALAM KENDARAAN</p>
</body>
</html>
