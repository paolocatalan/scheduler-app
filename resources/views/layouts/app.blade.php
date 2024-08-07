<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ config('app.name') }}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@300..700&family=Source+Code+Pro:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/htmx.org@1.9.12/dist/htmx.js" integrity="sha384-qbtR4rS9RrUMECUWDWM2+YGgN3U4V4ZncZ0BvUcg9FGct0jqXz3PUdVpU1p0yrXS" crossorigin="anonymous"></script>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
  <div id="page" class="site-page">
    <header id="masthead" class="site-header">
      @include('layouts.navigation')
    </header>
    @if (session()->has('message'))
      <div class="alert">
        <p>{{ session()->get('message') }}</p>
      </div>
    @endif
    @yield('content')
  </div>
</body>
</html>
