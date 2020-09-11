@extends('adminlte::page')

<link href="{{ asset('css/custom.css') }}" rel="stylesheet">

@section('content')
<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <a href="/medicalCases" class="btn btn-outline-dark"> Back</a>
          <a href="{{route('medicalCasesController.showCaseChanges',[$medicalCase->id])}}" class="btn btn-outline-dark float-right">View This Medical Case Changes</a>
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
                  <a class="nav-link" id="profile-tab" data-toggle="tab" href="#diagnoses" role="tab" aria-controls="profile" aria-selected="false">Diagnoses</a>
                </li>
              </ul>
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
                <div class="tab-content" id="myTabContent">
                  <div class="tab-pane fade show active" id="medicalCase" role="tabpanel" aria-labelledby="medicalCase-tab">
                    @if($medicalCaseInfo)
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
                        <a href="{{route('medicalCasesController.medicalCaseQuestion', [$medicalCase->id,$case->question->id])}}" class="btn btn-outline-light">Change Answer</a>
                      </div>
                      @endforeach
                    @else
                      <span>There is no information on this medical case</span>
                    @endif
                  </div>
                  <div class="tab-pane fade" id="diagnoses" role="tabpanel" aria-labelledby="diagnoses-tab">
                    @if($diagnoses)
                      @foreach($diagnoses as $diagnosis)
                      <div class="card">
                        <div class="card-header">
                          <span class="font-weight-bold">Label: </span>
                          <span>{{$diagnosis->label}}</span>
                        </div>
                        <div class="card-header">
                          <span class="font-weight-bold">diagnostic ID: </span>
                          <span>{{$diagnosis->diagnostic_id}}</span>
                        </div>
                        <div class="card-header">
                          <span class="font-weight-bold">Agreed: </span>
                          @if($diagnosis->agreed)
                          <span>Yes</span>
                          @else
                          <span>No</span>
                          @endif
                        </div>
                        <div class="card-header">
                          <span class="font-weight-bold">Diagnosis type: </span>
                          @if($diagnosis->proposed_additional)
                          <span>Proposed</span>
                          @else
                          <span>Additioanl</span>
                          @endif
                        </div>
                      </div>
                      @endforeach
                  @else
                      <span>There is no Diagnostic information on this medical case</span>
                  @endif
                  </div>
                </div>
                </div>
              </div>
              @else
                <span>The Medical Case does not exist</span>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  @stop
