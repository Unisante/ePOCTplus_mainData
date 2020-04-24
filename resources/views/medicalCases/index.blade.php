@extends('adminlte::page')

<link href="{{ asset('css/datatable.css') }}" rel="stylesheet">

@section('content')
<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header d-flex ">
          <span><h3>Medical Cases</h3></span>
          <button class="btn btn-outline-dark ml-auto p-2" onclick="compareMedicalCases()"> Compare</button>
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
            <div class="col-md-8 offset-md-2">
              @if(count($medicalCases)>0)
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">SN</th>
                    <th>checkbox</th>
                    <th scope="col">Date</th>
                    <th scope="col">Patient Name</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($medicalCases as $medicalCase)
                  <tr>
                    <th scope="row">{{ $loop->index }}</th>
                    <th><input type="checkbox" class="messageCheckbox" value="{{$medicalCase->id}}"></th>
                    <td>{{$medicalCase->created_at}}</td>
                    <td>{{$medicalCase->patient->first_name}} {{$medicalCase->patient->last_name}}</td>
                    <td><a href="/medicalCases/{{$medicalCase->id}}" class="btn btn-outline-dark"> Show Medical Case</a>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
              @else
              <p>No Medical Case Found</p>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@stop
