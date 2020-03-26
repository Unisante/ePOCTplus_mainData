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
          <div class="row sticky-top">
            <div class="col-md-6">
              @include('medicalCases.includes.firstMedicalCase')
            </div>
            <div class="col-md-6">
              @include('medicalCases.includes.secondMedicalCase')
            </div>
          </div>
          @foreach($medical_case_info as $case)
          <div class="card" style="background-color:#dadad7;">
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <span class="font-weight-bold">Question: </span> <span>{{$case['question']->label}}</span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="card">
                  @if(isset($case['first_case']))
                  @foreach($case['first_case'] as $first_case)
                  <span class="font-weight-bold">Answer:</span> {{$first_case->label}}
                  @endforeach
                  @endif
                </div>
              </div>
              <div class="col-md-6">
                <div class="card">
                  @if(isset($case['second_case']))
                  @foreach($case['second_case'] as $second_case)
                  <span class="font-weight-bold"> Answer: </span> {{$second_case->label}}
                  @endforeach
                  @endif
                </div>
              </div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div>
@include('layouts.highlightComparison')
@stop
