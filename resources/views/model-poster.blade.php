<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $modelName }} poster</title>

    <style>

        * {
            font-family: Arial, sans-serif;
            background-color: #16899b;
            padding: 0;
            margin: 0;

        }

        h1 {
            text-align: center;
            font-size: 60px;
            color: #ffffffff;
            margin: 80px;
            margin-bottom: 10px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        
        .container { 
            text-align: center;
        }

        h2 {
            display: block;
            color: #fff;
            border-radius: 10px;
            text-align: center;
            font-size: 24px;
            width: 20%;
            margin: 0px auto;
            margin-bottom: 60px;
            padding: 6px 12px;
            box-sizing: border-box;
        }

        #modelImage {
            width: 650px;
            max-height: 525px;
            padding-left: 500px;
            padding-right: 500px;
            margin-bottom: 0;
            display: flex; 
            flex-direction: column;
            align-items: center;
            
        }

        .logo {
            width: 400px;
            position: fixed;
            bottom: 50px;
            left: 50px;
        }

        .qr-code-container {
            text-align: center;
            color: #fff;
            font-size: 14px;
            position: fixed;
            bottom: 50px;
            right: 50px;
        }

        .qr-code-container img {
            width: 190px;
            height: 190px;
            margin: 3px;
            border: 15px solid #ffffff;
        }

        .qrText {
            font-size: 18px;
        }


    </style>
</head>
<body>
    <div class='container'>
        <h1>{{ $modelName }}</h1>
        <h2 style="background-color: {{ $educationColor }}">{{ $education }}</h2>
        <img src="{{ $modelImage }}" alt="{{ $model->name }}" id='modelImage' />
        <img src="{{ $logo }}" alt="Logo" class='logo'>
        <div class="qr-code-container">
            <p class="qrText">{{ $QrCodeText }}</p>
            <img src="data:image/svg+xml;base64,{{ $QrCode }}" alt="QR Code">
            <p class="QrPath">{{ $path }}</p>
        </div>
    </div>
</body>

</html>
