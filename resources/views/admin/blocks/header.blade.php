<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="images/favicon.ico" type="image/ico" />
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <title>{{ $title }} - Admin</title>

    <!-- Bootstrap -->
    <link href="{{ asset('admin/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ asset('admin/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <!-- NProgress -->
    <link href="{{ asset('admin/vendors/nprogress/nprogress.css') }}" rel="stylesheet">
    <!-- iCheck -->
    <link href="{{ asset('admin/vendors/iCheck/skins/flat/green.css') }}" rel="stylesheet">

    <!-- bootstrap-progressbar -->
    <link href="{{ asset('admin/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css') }}"
        rel="stylesheet">
    <!-- JQVMap -->
    <link href="{{ asset('admin/vendors/jqvmap/dist/jqvmap.min.css') }}" rel="stylesheet" />
    <!-- bootstrap-daterangepicker -->
    <link href="{{ asset('admin/vendors/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">

    <!-- Datatables -->
    <link href="{{ asset('admin/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css') }}"
        rel="stylesheet" />
    <link href="{{ asset('admin/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css') }}"
        rel="stylesheet" />
    <link href="{{ asset('admin/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}"
        rel="stylesheet" />
    <link href="{{ asset('admin/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css') }}"
        rel="stylesheet" />


    <!-- Custom Theme Style -->
    <link href="{{ asset('admin/build/css/custom.min.css') }}" rel="stylesheet">
    {{-- datetimepicker  --}}
    <link href="{{ asset('admin/assets/css/jquery.datetimepicker.min.css') }}" rel="stylesheet">
    <!-- Import CSS for Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <!-- Dropzone.js -->
    <link href="{{ asset('admin/vendors/dropzone/dist/min/dropzone.min.css') }}" rel="stylesheet" />
    {{-- Custom css by DevDien  --}}
    <link href="{{ asset('admin/assets/css/custom-css.css') }}" rel="stylesheet" />
</head>

<body class="nav-md">
