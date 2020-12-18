@extends('adminlte::page')

<link href="{{ asset('css/custom.css') }}" rel="stylesheet">

@section('content')
<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
      <div class="card-header"><a href="{{route('MedicalCasesController.show',[$medicalCase->id])}}" class="btn btn-outline-dark"> Back</a></div>
        <div class="card-body">
          @if (session('status'))
          <div class="alert alert-success" role="alert">
            {{ session('status') }}
          </div>
          @endif
          <div class="row">
            <div class="col-md-8 offset-md-2">
              @if($medicalCase)
              <div class="mb-2 card-color2" >
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
                  @if($question)
                  <div class="card">
                    <div class="card-header">
                      <span class="font-weight-bold">Label: </span>
                      <span>{{$question->label}}</span>
                    </div>
                    <div class="card-header">
                      <span class="font-weight-bold">Stage: </span>
                      <span>{{$question->stage}}</span>
                    </div>
                    <div class="card-header">
                      <span class="font-weight-bold">Description: </span>
                      @if($question->description)
                        <span>{{$question->description}}</span>
                      @else
                      <span>No Description was given</span>
                      @endif
                    </div>
                    {{ Form::open(['route' => ['medicalCaseAnswersController.update',$medicalCase,$question->id]]) }}
                    <div class="input-group pl-3">
                      <div class="input-group-prepend">
                        {{Form::label('answer', 'Answer', array('class' => 'input-group-text'))}}
                      </div>
                      @if($answer_type->value =='Date')
                        {{Form::date('answer', \Carbon\Carbon::now(), array('class' => 'form-control'))}}
                      @elseif($answer_type->value =='Float')
                        {{Form::text('answer', '',array('class' => 'form-control'))}}
                      @else
                        {{Form::select('answer', $answers, 'S', array('class' => 'custom-select'))}}
                      @endif
                    </div>
                    <div class="card-header">
                      {{Form::submit('Change Answer', array('class' => 'btn btn-outline-secondary'))}}
                    </div>
                    {{ Form::close() }}
                  </div>
                  @endif
                </div>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  @stop
