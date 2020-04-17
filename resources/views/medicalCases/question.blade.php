@extends('adminlte::page')

@section('css')
<style type="text/css">
  .required::after {
    content: "*";
    color: red;
  }

  .small-text {
    font-size: small;
  }
</style>
@stop

@section('content')
<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header"><a href="/medicalCases/{{$medicalCase->id}}" class="btn btn-outline-dark"> Back</a></div>
        <div class="card-body">
          @if (session('status'))
          <div class="alert alert-success" role="alert">
            {{ session('status') }}
          </div>
          @endif
          <div class="row">
            <div class="col-md-8 offset-md-2">
              @if($medicalCase)
                <div class="mb-2"  style="background-color:#ddd;">
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
                      <span>{{$question->description}}</span>
                    </div>
                  <form action="/medicalCases/{{$medicalCase->id}}/question/{{$question->id}}/update" method="POST">
                      @csrf
                    <div class="input-group pl-3">
                      <div class="input-group-prepend">
                        <label class="input-group-text" for="inputGroupSelect01">Answer</label>
                      </div>
                      <select class="custom-select" id="inputGroupSelect01" name="answer">
                        <option selected></option>
                        @foreach($answers as $answer)
                      <option value="{{$answer->id}}">{{$answer->label}}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="card-header">
                      <button type="submit" class="btn btn-outline-secondary">Change Answer</button>
                    </div>
                  </form>
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
