@extends('adminlte::page')

<link href="{{ asset('css/datatable.css') }}" rel="stylesheet">

@section('content')

@component('layouts.components.index',['instances' => $devices,
                                       'columns' => ['Name','Client ID'],
                                       'plurName' => 'Devices',
                                       'singName' => 'Device',
                                       'attributes' => ['name','oauth_client_id'],
                                       'url' => 'devices',
                                       'urlParam' => 'devices'])

@endcomponent


@stop