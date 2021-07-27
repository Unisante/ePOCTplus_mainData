@extends('adminlte::page')

<link href="{{ asset('css/datatable.css') }}" rel="stylesheet">

@section('content')

@component('layouts.components.edit',[
                                      'instance' => $device,
                                      'name' => 'Device',
                                      'url' => 'devices',
                                      'inputs' => [
                                                   'Name' => [
                                                              'keyword' => 'name',
                                                              'type' => 'text',]]])
    
@endcomponent



@endsection