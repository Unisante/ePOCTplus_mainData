@extends('adminlte::page')
@section('content')
<link href="{{ asset('css/datatable.css') }}" rel="stylesheet">
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Dashboard</div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="row">
                      <div class="col-md-4">
                        <div>
                          <span>User: </span>
                          <span>{{$currentUser->name}}</span>
                        </div>
                        <div>
                          @Foreach($currentUser->roles as $role)
                          <span>Role: </span>
                          <span>{{$role->name}}</span>
                          @endforeach
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div>
                          <span>Main Data Users: </span>
                          <span>{{$userCount}}</span>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div>
                         <span>Medical Cases: </span>
                         <span>{{$mdCases}}</span>
                        </div>
                        <div>
                          <span>Patients: </span>
                          <span>{{$patientCount}}</span>
                        </div>
                      </div>
                    </div>
                    <div class="row" style="margin-top:50px">
                      <div class="col" >
                        @include('layouts.chart')
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
