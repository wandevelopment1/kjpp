<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $groupLabel }} - {{ $penawaran->kepada_no_spk ?? 'Penawaran' }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #111827;
            margin: 0;
            padding: 32px;
        }
        .template-block + .template-block {
            page-break-before: always;
        }
    </style>
</head>
<body>
    @foreach($templates as $template)
        <div class="template-block">
            {!! $template['content'] !!}
        </div>
    @endforeach
</body>
</html>
