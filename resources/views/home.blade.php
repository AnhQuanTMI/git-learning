<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @vite(['resources/css/app.css', 'resources/scss/app.scss','resources/js/app.js'])
</head>
<body>
    <div class="container">
        <h1>
            Hello {{ Auth::user()->name }}!
        </h1>
        <form action="{{ route('welcome-email') }}" method="POST">
            @csrf
            <input type="hidden" name="id" value="{{ Auth::user()->id }}">
            <button type="submit" class="">hehehaha</button>
        </form>
    </div>
</body>
</html>