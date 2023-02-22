@props([
'title'
])
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" id="_csrf_token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>
    <link rel="icon" type="image/png" href="{{ asset('icons/512x512.png')  }}"/>
    <link rel="stylesheet" href="{{ asset('css/bootstrap5.min.css') }}">
<body>
<div class="container d-flex">
    <x-main.left />
    <div>
        {{ $slot }}
    </div>
</div>
<script></script>
</body>
</html>

