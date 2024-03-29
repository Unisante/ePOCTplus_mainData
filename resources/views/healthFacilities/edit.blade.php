@extends('adminlte::page')

<link href="{{ asset('css/datatable.css') }}" rel="stylesheet">

@section('content')

@component('layouts.components.edit',['instance' => $facility,
                                      'name' => 'Health Facility',
                                      'url' => 'health-facilities',
                                      'inputs' => ['Name' => ['keyword' => 'name',
                                                                'type' => 'text'
                                                            ],
                                                    'Country' => ['keyword' => 'country',
                                                                'type' => 'text',
                                                                ],
                                                    'Area' => ['keyword' => 'area',
                                                                'type' => 'text',
                                                            ],
                                                    'Pin Code' => ['keyword' => 'pin_code',
                                                                    'type' => 'text'
                                                                    ],
                                                    'Architecture' => ['keyword' => 'hf_mode',
                                                                        'type' => 'dropdown',
                                                                        'options' => ['standalone',
                                                                                    'client_server',
                                                                                    ],
                                                                        ],
                                                    'Latitude' => ['keyword' => 'lat',
                                                                'type' => 'text'],
                                                    'Longitude' => ['keyword' => 'long',
                                                                    'type' => 'text'],
                                                    'medAL-hub IP' => ['keyword' => 'local_data_ip',
                                                                    'type' => 'text'],
                                                    ],                                      ])

@endcomponent


@stop
