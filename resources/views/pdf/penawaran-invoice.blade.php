<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice Akhir - {{ $penawaran->kepada_no_spk ?? 'Penawaran' }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #111827;
            margin: 0;
            padding: 32px;
            line-height: 1.5;
        }

        .invoice-content {
            width: 100%;
        }

        .invoice-content p {
            margin-bottom: 12px;
        }

        .invoice-meta {
            margin-bottom: 24px;
            border-bottom: 1px solid #E5E7EB;
            padding-bottom: 12px;
        }

        .invoice-meta h1 {
            margin: 0;
            font-size: 20px;
        }

        .invoice-meta small {
            color: #6B7280;
        }
    </style>
</head>
<body>
    <div class="invoice-content">
        {!! $content !!}
    </div>
</body>
</html>
