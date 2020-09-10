@extends('adminlte::page')

<link href="{{ asset('css/custom.css') }}" rel="stylesheet">

@section('content')

<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header"><a href="{{route('patients.index')}}" class="btn btn-outline-dark"> Back</a></div>
        <div class="card-body">
          @if (session('status'))
          <div class="alert alert-success" role="alert">
            {{ session('status') }}
          </div>
          @endif
          <div class="row">
            <div class="col-md-8 offset-md-2">
              @if($patient)
              <div class="card card-color2">
                <div class="card-header">{{$patient->first_name}}'s Details</div>
                <div class="card-body">
                  <div>Patient Id: <span class="border-bottom">{{$patient->local_patient_id}}</span><br/></div>
                  <div>First Name: <span class="border-bottom">{{$patient->first_name}}</span><br/></div>
                  <div>Last Name: <span class="border-bottom">{{$patient->last_name}}</span><br/></div>
                  <div>Birth Date: <span class="border-bottom">{{$patient->birthdate}}</span><br/></div>
                  <div>Weight: <span class="border-bottom">{{$patient->weight}}</span><br/></div>
                  <div>Number of medical cases: <span class="border-bottom">{{$patient->medicalCases()->count()}}</span><br/></div>
                </div>
              </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@stop
