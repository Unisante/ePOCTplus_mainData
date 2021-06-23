@extends('adminlte::page')

<link href="{{ asset('css/datatable.css') }}" rel="stylesheet">

@section('content')

@component('layouts.components.details',['instance' => $facility,
                                      'name' => 'Health Facility',
                                      'url' => 'health-facilities',
                                      'identifierField' => 'name',
                                      'inputs' => ['Name' => 'name',
                                                   'Country' => 'country',
                                                   'Area' => 'area',
                                                   'Pin Code' => 'pin_code',],])

@endcomponent


@stop