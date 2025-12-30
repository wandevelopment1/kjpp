<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .content {
            background: white;
            padding: 20px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .field {
            margin-bottom: 15px;
        }
        .label {
            font-weight: bold;
            color: #555;
        }
        .message {
            white-space: pre-wrap;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="margin:0; color:#2d3748;">Pesan Baru dari Form Kontak</h2>
    </div>
    
    <div class="content">
        <div class="field">
            <span class="label">Subject:</span>
            <span>{{ $data['subject'] ?? '-' }}</span>
        </div>

        <div class="field">
            <span class="label">Nama:</span>
            <span>{{ $data['name'] }}</span>
        </div>
        
        <div class="field">
            <span class="label">Email:</span>
            <span>{{ $data['email'] }}</span>
        </div>
        <div class="field">
            <span class="label">Pesan:</span>
            <div class="message">{{ $data['message'] }}</div>
        </div>
    </div>
</body>
</html>
