@extends('adminlte::page')
@section('content')

@include('partials.errors')
@include('partials.success')

<div class="col-md-9 col-lg-9 col-sm-9 pull-left" style="background: white;">
  <h3 align="center">Create New Health Facility</h3>
  <!-- Example row of columns -->
  <div class="row col-sm-12 col-md-12 col-lg-12" style="background:white; margin: 10px">
    <form action="{{route('health-facilities.store')}}" method="post" id="userCreate">
      {!! csrf_field() !!}
      <div class="form-group row">
        <label for="name" class="col-md-3 col-form-label text-md-right">Name<span class="required"><font color="red">*</font></span></label>
        <div class="col-md-9">
          <input id="name"
          type="text"
          class="form-control "
          name="name"
          required autocomplete="name"
          autofocus >
        </div>
      </div>

      <div class="form-group row">
        <label for="country" class="col-md-3 col-form-label text-md-right">Country<span class="required"><font color="red">*</font></span></label>
        <div class="col-md-9">
          <input id="country"
          type="country"
          class="form-control"
          name="country"
          required autocomplete="country"
          autofocus >
        </div>
      </div>

      <div class="form-group row">
        <label for="area" class="col-md-3 col-form-label text-md-right">Area<span class="required"><font color="red">*</font></span></label>
        <div class="col-md-9">
          <input id="area"
          type="area"
          class="form-control"
          name="area"
          required autocomplete="area"
          autofocus >
        </div>
      </div>

      <div class="form-group row">
        <label for="name" class="col-md-3 col-form-label text-md-right">Pin Code<span class="required"><font color="red">*</font></span></label>
        <div class="col-md-9">
          <input id="name"
          type="text"
          class="form-control"
          name="pin_code"
          required autocomplete="name"
          autofocus >
        </div>
      </div>

      <div class="form-group row mb-0">
        <div class="col-md-6 offset-md-4">
          <button type="submit" class="btn btn-primary">
            Create
          </button>
        </div>
      </div>
    </form>

    @if($errors->any()){{ implode('', $errors->all('<div>:message</div>')) }}@endif
    @stop
