<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Deep Control</title>
    <meta content="TrustPics" property="og:title"/>
    <meta content="" property="og:image"/>
    <meta content="TrustPics" property="twitter:title"/>
    <meta content="" property="twitter:image"/>
    <meta content="width=device-width, initial-scale=1" name="viewport"/>

    @stack('custom-css')

</head>
<body class="@yield('body-class', '')">

@yield('content')

@stack('custom-js')

</body>
</html>
