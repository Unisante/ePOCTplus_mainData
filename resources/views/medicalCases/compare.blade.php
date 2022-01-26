@extends('adminlte::page')
<script src="{{ asset('js/highlight.js') }}" defer></script>
<link href="{{ asset('css/custom.css') }}" rel="stylesheet">
@section('content')
<div class="compare_container">
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header"><a href="{{route('medical-cases.index')}}" class="btn btn-outline-dark"> Back</a></div>
          <div class="card-body">
            @if (session('status'))
            <div class="alert alert-success" role="alert">
              {{ session('status') }}
            </div>
            @endif
            <div class="row sticky-top">
              <div class="col-md-6">
                @include('medicalCases.includes.firstMedicalCase')
              </div>
              <div class="col-md-6">
                @include('medicalCases.includes.secondMedicalCase')
              </div>
            </div>
            @if($medical_case_info)
              @foreach($medical_case_info as $case)
              <div class="card compare">
                <div class="row">
                  <div class="col-md-12">
                    <div class="question card">
                      <span><strong>Question</strong>: {{$case['question_label']}}</span>
                      <div class="dropdown-divider"></div>
                      {{-- <span><strong>Stage</strong>: {{$case['question_stage']}}</span> --}}
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                        <div class="answer card">
                          <span><strong>Answer</strong>: {{$case['first_answer']}}</span>
                        </div>
                  </div>
                  <div class="col-md-6">
                    <div class="answer card">
                      <span><strong>Answer</strong>: {{$case['second_answer']}}</span>
                    </div>
                  </div>
                </div>
              </div>
              @endforeach
            @else
              <div class="row">
                <div class="col-md-12">
                  <div class="question card">
                    <span><strong>Question</strong>: There is no information to Compare</span>
                    <div class="dropdown-divider"></div>
                  </div>
                </div>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@stop
