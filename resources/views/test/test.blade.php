@extends('adminlte::page')

<link href="{{ asset('css/datatable.css') }}" rel="stylesheet">

@section('content')

@component('layouts.components.create',['url' => 'health-facilities',
                                        'name' => 'Health Facility',
                                        'inputs' => ['Name' => 'name',
                                                     'Country' => 'country',
                                                     'Area' => 'area',
                                                     'Pin Code' => 'pin_code',],])

@endcomponent


@stop