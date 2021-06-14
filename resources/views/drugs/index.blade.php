@extends('adminlte::page')

<link href="{{ asset('css/datatable.css') }}" rel="stylesheet">

@section('content')
<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header d-flex ">
          <span><h3>Agreed Drugs</h3></span>
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
              @if(count($drugs)>0)
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">SN</th>
                    <th scope="col">case</th>
                    <th scope="col">diagnosis</th>
                    <th scope="col">drug</th>
                    <th scope="col">Medication Form</th>
                    <th scope="col">Description</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($drugs as $drug)
                  <tr>
                    <th scope="row">{{ $loop->index+1 }}</th>
                    <td>{{$drug->case_id}}</td>
                    <td>{{$drug->diagnosis_label}}</td>
                    <td>{{$drug->drug_label}}</td>
                    <td>{{$drug->formulation->medication_form}}</td>
                    <td>{{$drug->formulation->description}}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
              @else
              <p>No Drugs Found Found</p>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@stop
