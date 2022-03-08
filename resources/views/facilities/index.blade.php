@extends('adminlte::page')

<link href="{{ asset('css/datatable.css') }}" rel="stylesheet">

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex ">
                        <span>
                            <h3>Health Facilities</h3>
                        </span>
                    </div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        @include('layouts.datatable')
                        <div class="row">
                            <div class="col-md-10 offset-md-1">
                                @if (count($facilities) > 0)
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">SN</th>
                                                <th scope="col">Facility Name</th>
                                                <th scope="col">cases</th>
                                                <th scope="col">patients</th>
                                                <th scope="col">last case time</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($facilities as $facility)
                                                <tr>
                                                    <th scope="row">{{ $facility->group_id }}</th>
                                                    <td>{{ $facility->name }}</td>
                                                    <td>{{ $facility->patients_medical_cases_count }}</td>
                                                    <td>{{ $facility->patients_count }}</td>
                                                    <td>{{ $facility->patients_medical_cases->first()->updated_at ?? 'No cases' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    @else
                                        <span>
                                            <h3>No Facilities made yet</h3>
                                        </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
