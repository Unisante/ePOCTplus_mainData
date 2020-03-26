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
            <div class="col-md-8">
              @if($patient)
              <div class="card">
                <div class="card-header">{{$patient->first_name}}'s Details</div>
                <div class="card-body">
                  <div>First Name: <span class="border-bottom">{{$patient->first_name}}</span><br/></div>
                  <div>Last NAme: <span class="border-bottom">{{$patient->last_name}}</span><br/></div>
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
