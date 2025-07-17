<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <title>Login - {{ config('app.name') }}</title>
    @vite('resources/css/app.css')
</head>
<body class="font-sans antialiased bg-gray-50 min-h-screen">
    @yield('content')
</body>
</html>
