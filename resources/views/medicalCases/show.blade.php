@extends('adminlte::page')

<link href="{{ asset('css/custom.css') }}" rel="stylesheet">

@section('content')
<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
            <a href="/medicalcases" class="btn btn-outline-dark"> Back</a>
        <a href="{{route('MedicalCasesController.showCaseChanges',[$medicalCase->id])}}" class="btn btn-outline-dark float-right">View This Medical Case Changes</a>
        </div>
        <div class="card-body">
          @if (session('status'))
          <div class="alert alert-success" role="alert">
            {{ session('status') }}
          </div>
          @endif
          <div class="row">
            <div class="col-md-8 offset-md-2">

              <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                  <a class="nav-link active" id="home-tab" data-toggle="tab" href="#medicalCase" role="tab" aria-controls="home" aria-selected="true">Medical Case</a>
                </li>
                <li class="nav-item" role="presentation">
                  <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Diagnoses</a>
                </li>
                {{-- <li class="nav-item" role="presentation">
                  <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Contact</a>
                </li> --}}
              </ul>
              <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="medicalCase" role="tabpanel" aria-labelledby="home-tab">
                  @if($medicalCase)
                    <div class="showmdCard mb-2">
                      <div class="card-header">
                        <span class="font-weight-bold">Created At: </span>
                        <span>{{$medicalCase->created_at}}</span>
                      </div>
                      <div class="card-header">
                        <span class="font-weight-bold">Updated At: </span>
                        <span>{{$medicalCase->created_at}}</span> </div>
                      <div class="card-header">
                        <div>
                          <span class="font-weight-bold">Patient Name:</span>
                          <span class="border-bottom">
                            {{$medicalCase->patient->first_name}}
                            {{$medicalCase->patient->last_name}}
                          </span>
                        </div>
                      </div>
                    </div>
                    <div class="card" style="padding:10px">
                      @foreach($medicalCaseInfo as $case)
                      <div class="card">
                        <div class="card-header">
                          <span class="font-weight-bold">Label: </span>
                          <span>{{$case->question->label}}</span>
                        </div>
                        <div class="card-header">
                          <span class="font-weight-bold">Stage: </span>
                          <span>{{$case->question->stage}}</span>
                        </div>
                        <div class="card-header">
                          <span class="font-weight-bold">Description: </span>
                          @if($case->question->description)
                          <span>{{$case->question->description}}</span>
                          @else
                          <span>No Description</span>
                          @endif
                        </div>
                        <div class="card-header">
                          <span class="font-weight-bold">Answer: </span>
                          <span>{{$case->answer}}</span>
                        </div>
                        <a href="{{route('MedicalCasesController.medicalCaseQuestion', [$medicalCase->id,$case->question->id])}}" class="btn btn-outline-light">Change Answer</a>
                      </div>
                      @endforeach
                    </div>
                  @endif
                </div>
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">...</div>
                {{-- <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">...</div> --}}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@stop
