<!DOCTYPE html>
<html lang="en">
@include('inc.navbar')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('css/app.css')}}"> {{-- <- bootstrap css --}}
    <title>Contracargos @yield('title')</title>

</head>
<body>
{{-- That's how you write a comment in blade --}}
    @yield('content')


</body>
<footer>
    <script src="{{asset('js/app.js')}}"></script> {{-- <- bootstrap and jquery --}}
</footer>
</html>
