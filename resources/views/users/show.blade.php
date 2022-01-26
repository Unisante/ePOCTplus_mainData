@extends('adminlte::page')



@section('content_header')
@stop

@section('content')

@include('partials.errors')
@include('partials.success')


<div class="col-md-9 col-lg-9 col-sm-9 pull-left" style="background: white;">
  <!-- Example row of columns -->
  <h3 align="center">Showing User {{$user->name}}</h3>
  <div class="pull-left">
    <a class="btn btn-primary" href="{{ route('users.index') }}"> Back</a>
  </div>
  <div class="row col-sm-12 col-md-12 col-lg-12" pull-center style="background:white; margin: 10px">
      <div class="form-group row">
        <label for="name" class="col-md-3 col-form-label text-md-right">Full Name<span class="required"></span></label>
        <div class="col-md-9">
          <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{$user->name}}" disabled >
        </div>
      </div>
      <div class="form-group row">
        <label for="email" class="col-md-3 col-form-label text-md-right">{{ __('E-Mail Address') }}<span class="required"></span></label>

        <div class="col-md-9">
          <input id="email" type="email" disabled class="form-control @error('email') is-invalid @enderror" name="email" value="{{$user->email}}" required autocomplete="email">
        </div>
      </div>
    </div>
@stop
