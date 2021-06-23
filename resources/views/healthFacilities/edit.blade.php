@extends('adminlte::page')

<link href="{{ asset('css/datatable.css') }}" rel="stylesheet">

@section('content')

@component('layouts.components.edit',['instance' => $facility,
                                      'name' => 'Health Facility',
                                      'url' => 'health-facilities',
                                      'inputs' => ['Name' => 'name',
                                                   'Country' => 'country',
                                                   'Area' => 'area',
                                                   'Pin Code' => 'pin_code',],])

@endcomponent


@stop