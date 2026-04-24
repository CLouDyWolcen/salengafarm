<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Salenga Farm</title>
    <!-- Force favicon refresh -->
    <link rel="icon" type="image/x-icon" href="{{ asset('tree-leaf.ico') }}?v=2">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('tree-leaf.ico') }}?v=2">
    <link rel="icon" type="image/ico" href="{{ asset('tree-leaf.ico') }}?v=2">
    <!-- Apple Touch Icon -->
    <link rel="apple-touch-icon" href="{{ asset('tree-leaf.ico') }}?v=2">
    <!-- Force favicon refresh meta -->
    <meta name="msapplication-TileImage" content="{{ asset('tree-leaf.ico') }}?v=2">
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <div class="card">
            {{-- <img src="/images/inventory-logo.png" alt="Logo" class="brand-logo"> <!-- You'll need to add this image --> --}}
            {{ $slot }}
        </div>
    </div>
</body>
</html>
