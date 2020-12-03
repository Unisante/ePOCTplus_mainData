@extends('adminlte::page')
<script src="{{ asset('js/highlight.js') }}" defer></script>
<link href="{{ asset('css/custom.css') }}" rel="stylesheet">
@section('content')
<div class="compare_container">
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header"><a href="{{route('MedicalCasesController.index')}}" class="btn btn-outline-dark"> Back</a></div>
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
            @foreach($medical_case_info as $case)
            <div class="card compare">
              <div class="row">
                <div class="col-md-12">
                  <div class="question card">
                    <span><strong>Question</strong>: {{$case['question']->label}}</span>
                    <div class="dropdown-divider"></div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  @if(isset($case['first_case']))
                    @foreach($case['first_case'] as $first_case)
                      @if($loop->iteration == 2)
                      <div class="answer card">
                        <span><strong>Value</strong>: {{$first_case->value}}</span>
                      </div>
                      @endif
                    @endforeach
                  @endif
                </div>
                <div class="col-md-6">
                  @if(isset($case['second_case']))
                    @foreach($case['second_case'] as $second_case)
                      @if($loop->iteration == 2)
                      <div class="answer card">
                        <span><strong>Value</strong>:{{$second_case->value}}</span>
                      </div>
                      @endif
                    @endforeach
                  @endif
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  @if(isset($case['first_case']))
                    @foreach($case['first_case'] as $first_case)
                      @if($loop->iteration == 1)
                      <div class="answer card">
                        <span><strong>Answer</strong>:{{$first_case}}</span>
                      </div>
                      @endif
                    @endforeach
                  @endif
                </div>
                <div class="col-md-6">
                  @if(isset($case['second_case']))
                    @foreach($case['second_case'] as $second_case)
                      @if($loop->iteration == 1)
                      <div class="answer card">
                        <span><strong>Answer</strong>: {{$second_case}}</span>
                      </div>
                      @endif
                    @endforeach
                  @endif
                </div>
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@stop
