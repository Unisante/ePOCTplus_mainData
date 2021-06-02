@extends('adminlte::page')

<link href="{{ asset('css/datatable.css') }}" rel="stylesheet">

@section('content')
<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header d-flex ">
          <span>
            <h3>Devices</h3>
          </span>
        </div>
        <div class="card-body">
          @if (session('status'))
          <div class="alert alert-success" role="alert">
            {{ session('status') }}
          </div>
          @endif
          <passport-clients></passport-clients>
          <passport-authorized-clients></passport-authorized-clients>
          <passport-personal-access-tokens></passport-personal-access-tokens>
        </div>
      </div>
    </div>
  </div>
</div>
@stop
