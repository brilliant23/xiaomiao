<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title_prefix', config('adminlte.title_prefix', ''))
        @yield('title', config('adminlte.title', 'AdminLTE 2'))
        @yield('title_postfix', config('adminlte.title_postfix', ''))</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{{ mix('/css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/AdminLTE.min.css')}} ">
    <link rel="stylesheet"
          href="{{ asset('vendor/adminlte/dist/css/skins/skin-' . config('adminlte.skin', 'blue') . '.min.css')}} ">
    @yield('adminlte_css')
</head>
<body class="hold-transition @yield('body_class')">

@yield('body')
<script src="{{ asset('vendor/adminlte/vendor/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ mix('/js/admin.js') }}"></script>
@yield('adminlte_js')

</body>
</html>
