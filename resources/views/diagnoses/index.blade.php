@extends('adminlte::page')

<link href="{{ asset('css/datatable.css') }}" rel="stylesheet">

@section('content')
<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header d-flex ">
          <span><h3>Agreed Diagnoses</h3></span>
          <div class="ml-auto p-2">
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
              @if(count($diagnoses)>0)
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">SN</th>
                    <th scope="col">Case Id</th>
                    <th scope="col">Belogs To</th>
                    <th scope="col">Diagnosis</th>
                    <th scope="col">Proposed Or Additional</th>
                    <th scope="col">Came From</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($diagnoses as $diagnosis)
                  <tr>
                    <th scope="row">{{ $loop->index+1 }}</th>
                    <td>{{$diagnosis->local_medical_case_id}}</td>
                    <td>{{$diagnosis->local_patient_id}}</td>
                    <td>{{$diagnosis->diagnosis_label}}</td>
                    @if($diagnosis->proposed_additional)
                    <td>Proposed</td>
                    @else
                    <td>Additional</td>
                    @endif
                    <td>{{$diagnosis->facility_name}}</td>
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
