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
                                                   'Pin Code' => 'pin_code',
                                                   'Architecture' => 'hf_mode',
                                                   'Latitude' => 'lat',
                                                   'Longitude' => 'long',
                                                   'medAl-hub IP' => 'local_data_ip'],])

@endcomponent



@component('layouts.components.index',['instances' => $devices,
                                       'columns' => ['Name','Client ID'],
                                       'plurName' => 'Devices',
                                       'singName' => 'Device',
                                       'attributes' => ['name','oauth_client_id'],
                                       'url' => 'devices',
                                       'urlParam' => 'devices'])

@endcomponent


@stop