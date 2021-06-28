@extends('adminlte::page')

<link href="{{ asset('css/datatable.css') }}" rel="stylesheet">

@section('content')


@component('layouts.components.details',[
                                         'instance' => $device,
                                         'name' => 'Device',
                                         'url' => 'devices',
                                         'identifierField' => 'name',
                                         'inputs' => [
                                                      'Name' => 'name',
                                                      'MAC Address' => 'mac_address',
                                                      'Client ID' => 'oauth_client_id',]])
    
@endcomponent


@stop