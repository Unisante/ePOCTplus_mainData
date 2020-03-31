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
  .dataTables_filter input{
    border: 1px solid #ddd !important;
  }
</style>
@stop

@section('content')
<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header d-flex">
          <span><h3>Patients</h3></span>
          <button class="btn btn-outline-dark ml-auto p-2" onclick="comparePatients()"> Compare</button>
        </div>
        <div class="card-body">
          @if (session('status'))
          <div class="alert alert-success" role="alert">
            {{ session('status') }}
          </div>
          @endif
          @include('layouts.compareModal')
          <div class="row">
            <div class="col-md-8 offset-md-2">
              @if(count($patients)>0)
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">SN</th>
                    <th>checkbox</th>
                    <th scope="col">First Name</th>
                    <th scope="col">Last Name</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($patients as $patient)
                  <tr>
                    <th scope="row">{{ $loop->index }}</th>
                    <th><input type="checkbox" class="messageCheckbox" value="{{$patient->id}}"></th>
                    <td>{{$patient->first_name}}</td>
                    <td>{{$patient->last_name}}</td>
                    <td><a href="/patient/{{$patient->id}}" class="btn btn-outline-dark"> Show Patient</a></td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
              @else
              <p>No Patients Found</p>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@stop
