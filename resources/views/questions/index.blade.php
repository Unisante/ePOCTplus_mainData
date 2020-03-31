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
  .dataTables_filter input{
    border: 1px solid #ddd !important;
  }
</style>
@stop

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
            <div class="col-md-8 offset-md-2">
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
                    <td><a href="/question/{{$question->id}}" class="btn btn-outline-dark"> Show question</a></td>
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
