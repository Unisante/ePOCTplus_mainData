@extends('adminlte::page')

<link href="{{ asset('css/datatable.css') }}" rel="stylesheet">

@section('content')

@component('layouts.components.create',['url' => 'devices',
                                        'name' => 'Device',
                                        'inputs' => ['Name' => ['keyword' => 'name',
                                                                'type' => 'text'],
                                        ]
                                        ])

@endcomponent


@stop