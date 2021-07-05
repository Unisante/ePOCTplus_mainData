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
    <example-component></example-component>
    <Link-Button title="TestButton" url="{{route("health-facilities.index")}}"></Link-Button>
    <Index-Table v-bind:columns="['Name','Client ID','Created At']" 
                 v-bind:data='{{$devices}}'
                 v-bind:keys="['name','oauth_client_id','created_at']"
                 resource_url="{{route("devices.index")}}"
                 :actions="['view','edit','delete']"
                 :custom_actions="[{'title':'custom',
                                    'method':'link',
                                    'before_url':'{{route("devices.index")}}',
                                    'after_url':'edit'}]"></IndexTable>
    </div>
</body>

@endsection
