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
                  <div>First Name: <span class="border-bottom">{{$first_patient->first_name}}</span><br/></div>
                  <div>lastNAme: <span class="border-bottom">{{$first_patient->last_name}}</span><br/></div>
                  <div>Number of medical cases: <span class="border-bottom">{{$first_patient->medicalCases()->count()}}</span><br/></div>
                </div>
              </div>
              <div class="card">
                <div class="card-header">{{$first_patient->first_name}}'s medical cases</div>
                <div class="card-body">
                  Name;lastNAme;Number of medical cases
                </div>
              </div>
              @else
              echo "he doesnt";
              @endif
            </div>
            <div class="col-md-6">
              @if($second_patient)
              <div class="card">
                <div class="card-header">{{$second_patient->first_name}}'s Details</div>
                <div class="card-body">
                  <div>First Name: <span class="border-bottom">{{$second_patient->first_name}}</span><br/></div>
                  <div>lastNAme: <span class="border-bottom">{{$second_patient->last_name}}</span><br/></div>
                  <div>Number of medical cases: <span class="border-bottom">{{$second_patient->medicalCases()->count()}}</span><br/></div>
                </div>
              </div>
              <div class="card">
                <div class="card-header">{{$second_patient->first_name}}'s medical cases</div>
                <div class="card-body">
                  Name;lastNAme;Number of medical cases
                </div>
              </div>
              @else
              echo "he doesnt";
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@stop
