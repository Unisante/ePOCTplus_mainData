@extends('adminlte::page')

<link href="{{ asset('css/datatable.css') }}" rel="stylesheet">

@section('content')
<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header d-flex">
          <span><h3>Patients</h3></span>
          <div class="ml-auto p-2">
            {{-- <a href="{{route('patients.patientIntoCsv')}}" class="btn btn-outline-dark">Export Csv</a>
            <a href="{{route('patients.patientIntoExcel')}}" class="btn btn-outline-dark"> Export Excel</a> --}}
          {{-- <button class="btn btn-outline-dark " onclick="comparePatients()"> Compare</button> --}}
          </div>
        </div>
        <div class="card-body">
          @if (session('status'))
          <div class="alert alert-success" role="alert">
            {{ session('status') }}
          </div>
          @endif
          @include('layouts.compareModal')
          @include('layouts.datatable')
          <div class="row">
            <div class="col-md-12">
              @if(count($patients)>0)
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">SN</th>
                    <th scope="col">Patient Uid</th>
                    <th scope="col">First Name</th>
                    <th scope="col">Middle Name</th>
                    <th scope="col">Last Name</th>
                    <th scope="col">Birthdate</th>
                    <th scope="col">Facility</th>
                    <th scope="col">Status</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($patients as $patient)
                  <tr>
                    <td scope="row">{{ $patient->id }}</td>
                    <td>{{$patient->local_patient_id}}</td>
                    <td>{{$patient->first_name}}</td>
                    <td>{{$patient->middle_name}}</td>
                    <td>{{$patient->last_name}}</td>
                    <td>{{$patient->birthdate}}</td>
                    <td>{{$patient->facility_name}}</td>
                    @if($patient->merged)
                    <td>Merged</td>
                    @else
                    <td>Active</td>
                    @endif
                    <td><a href="{{route('patients.show',[$patient->id])}}" class="btn btn-outline-dark"> Show Patient</a></td>
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


