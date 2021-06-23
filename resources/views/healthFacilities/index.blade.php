@extends('adminlte::page')

<link href="{{ asset('css/datatable.css') }}" rel="stylesheet">

@section('content')

@component('layouts.components.index',['instances' => $facilities,
                                       'columns' => ['Name','Country','Area'],
                                       'plurName' => 'Health Facilities',
                                       'singName' => 'Health Facility',
                                       'attributes' => ['name','country','area'],
                                       'url' => 'health-facilities',
                                       'urlParam' => 'health_facilities'])

@endcomponent


@stop