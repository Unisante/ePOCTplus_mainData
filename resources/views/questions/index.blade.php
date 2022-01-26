@extends('adminlte::page')
@section('content')
<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header d-flex justify-content-center">
          <span><h3>Questions</h3></span>
        </div>
        <div class="card-body">
          @if (session('status'))
          <div class="alert alert-success" role="alert">
            {{ session('status') }}
          </div>
          @endif
          @include('layouts.compareModal')
          <div class="row">
            <div class="col-md-12">
              @if(count($questions)>0)
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">Question Id</th>
                    <th scope="col">Label</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($questions as $question)
                  <tr>
                    <td>{{$question->id}}</td>
                    <td>{{$question->label}}</td>
                    <td><a href="{{route('questions.show',[$question->id])}}" class="btn btn-outline-dark"> Show question</a></td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
              @else
              <p>No questions Found</p>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@stop
