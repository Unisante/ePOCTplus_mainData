@extends('adminlte::page')

<link href="{{ asset('css/datatable.css') }}" rel="stylesheet">

@section('content')
<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header d-flex ">
          <span><h3>Delayed Follow-ups</h3></span>
        </div>
        <div class="card-body">
          @if (session('status'))
          <div class="alert alert-success" role="alert">
            {{ session('status') }}
          </div>
          @endif
          @include('layouts.datatable')
          <div class="row">
            <div class="col-md-10 offset-md-1">
              @if(count($unfollowed)>0)
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">SN</th>
                    <th scope="col">consultation_ID</th>
                    <th scope="col">patient_id</th>
                    <th scope="col">facility_id</th>
                    <th scope="col">consultation date</th>
                    <th scope="col">village_name</th>
                    {{-- <th scope="col">Actions</th> --}}
                  </tr>
                </thead>
                <tbody>
                  @foreach($unfollowed as $followup)
                  <tr>
                    <th scope="row">{{ $loop->index+1 }}</th>
                    <td>{{$followup->getConsultationId()}}</td>
                    <td>{{$followup->getPatientId()}}</td>
                    <td>{{$followup->getFacilityId()}}</td>
                    <td>{{$followup->getConsultationDate()}}</td>
                    <td>{{$followup->getVillage()}}</td>
                    {{-- <td>Actions</td> --}}
                  </tr>
                  @endforeach
                </tbody>
                @else
                  <span><h3>No Delayed follow ups</h3></span>
                @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@stop
