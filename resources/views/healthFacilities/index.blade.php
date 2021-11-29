@extends('adminlte::page')

 <!-- The contents of the layout/app.bade.php are included so that the view components work correctly -->
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
      {{-- {{ config('app.name', 'Laravel') }} --}}
      Liwi Main Data
    </title>


    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/jquery.min.js')}}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/resetPassword.css') }}" rel="stylesheet">
    <!-- Latest compiled and minified CSS -->
{{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> --}}
</head>


@section('content')

<body>
    <div id="app" class="colorGrey">
        <health-facilities :health_facilities="{{$healthFacilities}}"
                           health_facilities_route="{{route("health-facilities.index")}}"
                           devices_route="{{route("devices.index")}}"></health-facilities>
    </div>
</body>

@stop
