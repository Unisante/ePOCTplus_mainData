@extends('adminlte::page')

@section('css')
<style type="text/css">
  .required::after {
    content: "*";
    color: red;
  }

  .small-text {
    font-size: small;
  }
</style>

@stop

@section('content')

<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header"><a href="/patients" class="btn btn-outline-dark"> Back</a></div>
        <div class="card-body">
          @if (session('status'))
          <div class="alert alert-success" role="alert">
            {{ session('status') }}
          </div>
          @endif
          <div class="row">
            <div class="col-md-5">
              @if($first_patient)
              <div class="card">
                <div class="card-header">{{$first_patient->first_name}}'s Details</div>
                <div class="card-body">
                  <div>First name: <span class="border-bottom">{{$first_patient->first_name}}</span><br/></div>
                  <div>last name: <span class="border-bottom">{{$first_patient->last_name}}</span><br/></div>
                  <div>Number of medical cases: <span class="border-bottom">{{$first_patient->medicalCases()->count()}}</span><br/></div>
                </div>
              </div>
              <div class="card">
                <div class="card-header">{{$first_patient->first_name}}'s Medical Cases</div>
                @foreach($first_patient->medicalCases as $medicalCase)
                <div class="card-body">
                  <div>Medical Case: <span class="border-bottom">Diagnostic</span><br/></div>
                  <div>Date: <span class="border-bottom">{{$medicalCase->created_at}}</span><br/></div>
                </div>
                @endforeach
              </div>
              @else
              <div class="card-header">No first Patient</div>
              @endif
            </div>
            <div class="col-md-6">
              @if($second_patient)
              <div class="card">
                <div class="card-header">{{$second_patient->first_name}}'s Details</div>
                <div class="card-body">
                  <div>First name: <span class="border-bottom">{{$second_patient->first_name}}</span><br/></div>
                  <div>last name: <span class="border-bottom">{{$second_patient->last_name}}</span><br/></div>
                  <div>Number of medical cases: <span class="border-bottom">{{$second_patient->medicalCases()->count()}}</span><br/></div>
                </div>
              </div>
              <div class="card">
                <div class="card-header">{{$second_patient->first_name}}'s Medical Cases</div>
                @foreach($second_patient->medicalCases as $medicalCase)
                <div class="card-body">
                  <div>Medical Case: <span class="border-bottom">Diagnostic</span><br/></div>
                  <div>Date: <span class="border-bottom">{{$medicalCase->created_at}}</span><br/></div>
                </div>
                @endforeach
              </div>
              @else
              <div class="card">
                <div class="card-header">No Second Patient</div>
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
