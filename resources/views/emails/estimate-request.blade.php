<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nuova richiesta riparazione</title>
    <style>
        /* Basic Reset */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Figtree', Arial, sans-serif;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            max-width: 150px;
        }

        .title {
            color: #222222;
            /* Tailwind Red-500 */
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .content {
            line-height: 1.6;
            font-size: 16px;
            color: #333333;
        }

        .content p {
            margin-bottom: 12px;
        }

        .label {
            font-weight: 600;
            color: #4B5563;
            /* Tailwind Gray-700 */
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #6B7280;
            /* Tailwind Gray-500 */
        }
    </style>
</head>

<body style="background-color: #F3F4F6; padding: 20px;">
    <div class="container">
        <!-- Logo Section -->
        <div class="header">
            <img src="{{ asset('images/logo.svg') }}" alt="logo">
        </div>

        <!-- Title Section -->
        <h2 class="title">Nuova Richiesta Riparazione</h2>

        <!-- Content Section -->
        <div class="content">
            <p><span class="label">Cliente:</span> {{ $data['customer'] }}</p>
            <p><span class="label">Descrizione riparazione:</span> {{ $data['description'] }}</p>
            <p><span class="label">Data:</span> {{ $data['date'] }}</p>
            <p><span class="label">Tecnico:</span> {{ $data['mechanic'] }}</p>
            <p><span class="label">Marca:</span> {{ $data['brand'] }}</p>
            <p><span class="label">Targa/Telaio:</span> {{ $data['plate'] }}</p>
            <p><span class="label">Immatricolazione:</span> {{ $data['immatricolazione'] }}</p>
            <p><span class="label">Numero di km:</span> {{ $data['km'] }} km</p>
        </div>

        <!-- Footer Section -->
        <div class="footer">
            <p>Questa è una email automatica, si prega di non rispondere.</p>
        </div>
    </div>
</body>

</html>
