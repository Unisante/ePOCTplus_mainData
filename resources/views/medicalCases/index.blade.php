@extends('adminlte::page')

<link href="{{ asset('css/datatable.css') }}" rel="stylesheet">

@section('content')
<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header d-flex ">
          <span><h3>Medical Cases</h3></span>

          <div class="ml-auto p-2">
            {{-- <a href="{{route('medicalCasesController.medicalCaseIntoCsv')}}" class="btn btn-outline-dark">Export Csv</a>
            <a href="{{route('medicalCasesController.medicalCaseIntoExcel')}}" class="btn btn-outline-dark"> Export Excel</a> --}}
            {{-- <button class="btn btn-outline-dark" onclick="compareMedicalCases()"> Compare</button> --}}

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
            <div class="col-md-10 offset-md-1">
              @if(count($medicalCases)>0)
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">SN</th>
                    <th scope="col">Case Id</th>
                    <th scope="col">Belogs TO</th>
                    <th scope="col">Date Created</th>
                    <th scope="col">Came From</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($medicalCases as $medicalCase)
                  <tr>
                    <th scope="row">{{ $loop->index+1 }}</th>
                    <td>{{$medicalCase->local_medical_case_id}}</td>
                    <td>{{$medicalCase->patient->local_patient_id}}</td>
                    <td>{{$medicalCase->consultation_date?$medicalCase->consultation_date:$medicalCase->created_at}}</td>
                    <td>{{$medicalCase->facility_name}}</td>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
              @else
              <p>No Medical Cases Found</p>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@stop
