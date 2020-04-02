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
        <div class="card-header">
            <a href="/questions" class="btn btn-outline-dark"> Back</a>
        </div>
        <div class="card-body">
          @if (session('status'))
          <div class="alert alert-success" role="alert">
            {{ session('status') }}
          </div>
          @endif
          <div class="row">
            <div class="col-md-8 offset-md-2">
              @if($question)
                <div class="mb-2"  style="background-color:#ddd;">
                  <div class="card-header">
                    <span class="font-weight-bold">Created At: </span>
                    <span class="ml-2">{{$question->created_at}}</span>
                  </div>
                  <div class="card-header">
                    <span class="font-weight-bold">Question Id: </span>
                    <span class="ml-2">{{$question->id}}</span> </div>
                  <div class="card-header">
                    <div>
                      <span class="font-weight-bold">Label:</span>
                      <span class="border-bottom ml-2">
                        {{$question->label}}
                      </span>
                    </div>
                  </div>
                  <div class="card-header">
                    <div>
                      <span class="font-weight-bold">Number of answers: </span>
                      <span class="border-bottom ml-2">
                        {{$question->answers->count()}}
                      </span>
                    </div>
                  </div>
                </div>
                <div class="card" style="padding:10px">
                  @if(count($question->answers) > 0)
                    @foreach($question->answers as $answer)
                    <div class="card">
                      <div class="card-header">
                        <span class="font-weight-bold">Answer: </span>
                        <span>{{$answer->label}}</span>
                      </div>
                    </div>
                    @endforeach
                  @else
                    <div>
                      <h2>There are no answers for this question</h2>
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
