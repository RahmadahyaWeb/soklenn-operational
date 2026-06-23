<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    @include('partials.seo')

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @fluxAppearance

</head>

<body class="bg-white text-[#0b3d2c] overflow-x-hidden">

    @yield('content')

</body>

</html>
