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
        <div class="card-body">
          @if (session('status'))
          <div class="alert alert-success" role="alert">
            {{ session('status') }}
          </div>
          @endif
          <div class="row justify-content-center">
            <form action="/patients/merge" method="POST">
              @csrf
            <table class="table">
              <thead>
                <th scope="col">Demographics</th>
                <th scope="col">First Patient</th>
                <th scope="col">Second Patient</th>
              </thead>
              <tbody>
                <tr>
                  <td>First Name:</td>
                  <td><input type="radio"  name="first_name" value="{{$first_patient->first_name}}" checked>{{$first_patient->first_name}}</td>
                  <td><input type="radio"  name="first_name" value="{{$second_patient->first_name}}">{{$second_patient->first_name}}</td>
                </tr>
                <tr>
                  <td>Last Name:</td>
                  <td><input type="radio" name="last_name" value="{{$first_patient->last_name}}" checked>{{$first_patient->last_name}}</td>
                  <td><input type="radio" name="last_name" value="{{$second_patient->last_name}}">{{$second_patient->last_name}}</td>
                </tr>
                <tr>
                  <td>Number of medical Cases:</td>
                  <td><input type="radio" name="medical_cases" value="{{$first_patient->medicalCases()->count()}}" checked>{{$first_patient->medicalCases()->count()}}</td>
                  <td><input type="radio" name="medical_cases" value="{{$second_patient->medicalCases()->count()}}">{{$second_patient->medicalCases()->count()}}</label></td>
                </tr>
                
              </tbody>
            </table>
            <input id="patient_id" type="text" name="firstp_id" value="{{$first_patient->id}}" hidden>
            <input id="patient_id" type="text" name="secondp_id" value="{{$second_patient->id}}" hidden>
            <input type="submit" name="button" value="Merge"/></form>
          </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@include('layouts.highlightComparison')
@stop
