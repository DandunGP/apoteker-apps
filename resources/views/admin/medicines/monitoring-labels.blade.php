<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Label Stok Apotek Pakis</title>
    <style>
        @page {
            size: A4;
            margin: 10mm;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Courier New', monospace;
            background: #fff;
            color: #000;
            font-size: 10px;
        }

        .labels-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8mm;
        }

        .label-card {
            border: 1.5px dashed #000;
            border-radius: 4px;
            padding: 8px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 48mm;
            background: white;
            page-break-inside: avoid;
        }

        .label-header {
            border-bottom: 1px solid #000;
            padding-bottom: 4px;
            text-align: center;
            font-weight: bold;
            font-size: 9px;
            text-transform: uppercase;
        }

        .label-body {
            margin-top: 6px;
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .med-name {
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
        }

        .info-label {
            font-weight: bold;
        }

        .barcode-area {
            border: 1px solid #000;
            height: 10mm;
            margin-top: 8px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-size: 8px;
            font-weight: bold;
            letter-spacing: 2px;
            background: #fff;
            position: relative;
        }

        /* Faux barcode lines for extreme visual quality */
        .barcode-lines {
            width: 90%;
            height: 6mm;
            background: repeating-linear-gradient(
                90deg,
                #000,
                #000 1px,
                #fff 1px,
                #fff 3px,
                #000 3px,
                #000 4px,
                #fff 4px,
                #fff 6px
            );
        }

        .barcode-text {
            margin-top: 1px;
            font-size: 8px;
            letter-spacing: 0.5px;
        }

        .label-footer {
            margin-top: 4px;
            text-align: center;
            font-size: 7.5px;
            font-weight: bold;
            border-top: 1px dashed #000;
            padding-top: 3px;
        }
    </style>
</head>
<body>

    <div class="labels-grid">
        @forelse($items as $item)
            <div class="label-card">
                <div class="label-header">
                    Apotek Pakis Medika Utama
                </div>
                
                <div class="label-body">
                    <div class="med-name">{{ $item->nama }}</div>
                    
                    <div class="info-row">
                        <span class="info-label">KODE:</span>
                        <span>{{ $item->kode }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">BATCH:</span>
                        <span>{{ $item->no_batch }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">EXP:</span>
                        <span style="font-weight: bold;">{{ $item->tanggal_kadaluwarsa }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">STOK:</span>
                        <span style="font-weight: bold;">{{ $item->total_stock }} {{ $item->satuan }}</span>
                    </div>
                </div>

                <div class="barcode-area">
                    <div class="barcode-lines"></div>
                    <div class="barcode-text">*{{ $item->kode }}*</div>
                </div>

                <div class="label-footer">
                    DISIMPAN PADA SUHU 15-25°C (CDOB)
                </div>
            </div>
        @empty
            <div style="grid-column: span 3; text-align: center; padding: 40px; font-weight: bold;">
                Tidak ada data obat untuk label.
            </div>
        @endforelse
    </div>

</body>
</html>
