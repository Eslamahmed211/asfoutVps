<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>{{ settings('website_title') }}</title>

<link rel="stylesheet" href="{{ asset('assets/admin/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/css/sweetalert2.min.css') }}">

@if (!request()->is('admin/home'))
    <link rel="stylesheet" href="{{ asset('assets/admin/css/jquery-ui.min.css') }}">
@endif



@if (!request()->is('admin/orders') && !request()->is('admin/orders/search') && !request()->is('admin/home'))



    @if (auth()->user()->role == 'admin')
        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
        <link href="https://cdn.quilljs.com/1.3.6/quill.core.css" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('assets/admin/css/ssi-uploader.min.css') }}">
        <link rel="stylesheet" href="{{ asset('vendor/file-manager/css/file-manager.css') }}">
    @endif


@endif

@if (!request()->is('admin/home'))


    <link rel="stylesheet" href="{{ asset('assets/admin/css/time.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap2-toggle.min.css') }}">

    @if (auth()->user()->role == 'admin')
        <link rel="stylesheet" href="{{ asset('assets/admin/css/toastr.min.css') }}">
    @endif
@endif




<link rel="stylesheet" href="{{ asset('assets/admin/css/adminstyle.css?ver=2.1') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/css/mobile.css?ver=1.7') }}" media="screen and (max-width: 992px)">



@if (!request()->is('admin/orders') && !request()->is('admin/orders/search')  && !request()->is('admin/home'))
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/css/selectize.default.min.css"
        integrity="sha512-pTaEn+6gF1IeWv3W1+7X7eM60TFu/agjgoHmYhAfLEU8Phuf6JKiiE8YmsNC0aCgQv4192s4Vai8YZ6VNM6vyQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endif


@php
    $fav = str_replace('public', 'storage', settings('website_fav'));
@endphp

<link rel="shortcut icon" href="{{ asset("$fav") }}" type="image/x-icon">




<script>
    if (
        localStorage.getItem("color") &&
        localStorage.getItem("xbuttonBorder") &&
        localStorage.getItem("xbuttonBackground")
    ) {
        let rootStyles = document.documentElement.style;

        let color = localStorage.getItem("color");
        rootStyles.setProperty("--mainColor", `${color}`);

        let xbuttonBorder = localStorage.getItem("xbuttonBorder");
        rootStyles.setProperty("--xbutton-border", `${xbuttonBorder}`);

        let xbuttonBackground = localStorage.getItem("xbuttonBackground");
        rootStyles.setProperty("--xbutton-background", `${xbuttonBackground}`);
    }
</script>

@yield('style')
