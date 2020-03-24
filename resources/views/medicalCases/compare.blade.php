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
        <div class="card-header"><a href="/medicalCases" class="btn btn-outline-dark"> Back</a></div>
        <div class="card-body">
          @if (session('status'))
          <div class="alert alert-success" role="alert">
            {{ session('status') }}
          </div>
          @endif
          <div class="row">
            <div class="col-md-6">
              @include('medicalCases.includes.firstMedicalCase')
            </div>
            <div class="col-md-6">
              @include('medicalCases.includes.secondMedicalCase')
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@include('layouts.highlightComparison')
@stop
