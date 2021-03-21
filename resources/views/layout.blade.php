<!DOCTYPE html>
<html lang='pt-BR'>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- CSS only -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/f15fe4cbae.js" crossorigin="anonymous"></script>
    <title>Séries Controller</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-2 d-flex justify-content-between">
        <a class="navbar-brand" href="{{ route('list_series') }}">Home</a>
        @auth
        <a href="/logout" class="text-danger">Log out</a>
        @endauth

        @guest
        <a href="/login">Login</a>
        @endguest
    </nav>

    <div class="container">
        <div class="jumbotron">
            <h1>@yield('header')</h1>
        </div>
        @yield('content')
    </div>
</body>

</html>