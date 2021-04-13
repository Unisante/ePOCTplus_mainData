@extends('adminlte::page')

<link href="{{ asset('css/custom.css') }}" rel="stylesheet">

@section('content')

<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header"><a href="{{route('Patients.index')}}" class="btn btn-outline-dark"> Back</a></div>
        <div class="card-body">
          @if (session('status'))
          <div class="alert alert-success" role="alert">
            {{ session('status') }}
          </div>
          @endif
          <div class="row">
            <div class="col-md-8 offset-md-2">
              @if($patient)
              <div class="card card-color2 test-white">
                <div class="card-header">{{$patient->first_name}}'s Details</div>
                <div class="card-body">
                  <div class="d-flex justify-content-between"> <span>Patient Id:</span>  <span class="border-bottom">{{$patient->local_patient_id}}</span></div>
                  <div class="d-flex justify-content-between"> <span>Other Id:</span>  <span class="border-bottom">{{$patient->other_id}}</span></div>
                  <div class="d-flex justify-content-between"> <span>First Name:</span>  <span class="border-bottom">{{$patient->first_name}}</span></div>
                  <div class="d-flex justify-content-between"> <span>Middle Name:</span>  <span class="border-bottom">{{$patient->middle_name}}</span></div>
                  <div class="d-flex justify-content-between"> <span>Last Name:</span>  <span class="border-bottom">{{$patient->last_name}}</span></div>
                  <div class="d-flex justify-content-between"> <span>Birth Date:</span>  <span class="border-bottom">{{$patient->birthdate}}</span></div>
                  <div class="d-flex justify-content-between"> <span>Weight: </span> <span class="border-bottom">{{$patient->weight}}</span></div>
                  <div class="d-flex justify-content-between"> <span>Gender:</span>  <span class="border-bottom">{{$patient->gender}}</span></div>
                  <div class="d-flex justify-content-between"> <span>Group Id:</span>  <span class="border-bottom">{{$patient->group_id}}</span></div>
                  <div class="d-flex justify-content-between"> <span>Related Ids:</span>  <span class="border-bottom">{{$patient->related_ids}}</span></div>
                  <div class="d-flex justify-content-between"> <span>Number of medical cases:</span>  <span class="border-bottom">{{$patient->medicalCases()->count()}}</span></div>
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
