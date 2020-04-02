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
        <div class="card-header"><a href="/patients" class="btn btn-outline-dark"> Back</a></div>
        <div class="card-body">
          @if (session('status'))
          <div class="alert alert-success" role="alert">
            {{ session('status') }}
          </div>
          @endif
          <div class="row justify-content-center">
              <div class="col-md-4">
                <a href="/patients/duplicates/first_name" class="btn btn-outline-secondary">By First Name</a>
                <a href="/patients/duplicates/last_name" class="btn btn-outline-secondary">By Last Name</a>
              </div>
              <div class="col-md-4">
                <a href="" class="btn btn-outline-secondary">By Date Of Birth </a>
                <a href="" class="btn btn-outline-secondary">By National Id</a>
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@stop
