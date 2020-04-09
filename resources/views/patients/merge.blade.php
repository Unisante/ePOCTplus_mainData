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
  #label_id{
    margin-left: 5px;
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
                    <td>
                      <input type="radio"  name="first_name" value="{{$first_patient->first_name}}" checked>
                      <label id="label_id">{{$first_patient->first_name}}</label>
                      <div class="check"></div>
                    </td>
                    <td>
                      <input type="radio"  name="first_name" value="{{$second_patient->first_name}}">
                      <label id="label_id">{{$second_patient->first_name}}</label>
                      <div class="check"></div>
                    </td>
                  </tr>
                  <tr>
                    <td>Last Name:</td>
                    <td>
                      <input type="radio" name="last_name" value="{{$first_patient->last_name}}" checked>
                      <label id="label_id">{{$first_patient->last_name}}</label>
                      <div class="check"></div>
                    </td>
                    <td>
                      <input type="radio" name="last_name" value="{{$second_patient->last_name}}">
                      <label id="label_id">{{$second_patient->last_name}}</label>
                      <div class="check"></div>
                    </td>
                  </tr>
                  <tr>
                    <td>Number of medical Cases:</td>
                    <td>
                      <label>{{$first_patient->medicalCases()->count()}}</label>
                    </td>
                    <td>
                      <label>{{$second_patient->medicalCases()->count()}}</label>
                    </td>
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
