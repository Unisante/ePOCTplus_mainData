@extends('adminlte::page')
@section('content')

@include('partials.errors')
@include('partials.success')

<div class="col-md-9 col-lg-9 col-sm-9 pull-left" style="background: white;">
  <h3 align="center">Register New User </h3>
  <!-- Example row of columns -->
  <div class="row col-sm-12 col-md-12 col-lg-12" style="background:white; margin: 10px">
    <form action="{{route('users.store')}}" method="post" id="userCreate">
      {!! csrf_field() !!}
      <div class="form-group row">
        <label for="name" class="col-md-3 col-form-label text-md-right">Full Name<span class="required"><font color="red">*</font></span></label>
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
        <label for="email" class="col-md-3 col-form-label text-md-right">E-mail Address<span class="required"><font color="red">*</font></span></label>
        <div class="col-md-9">
          <input id="email"
          type="email"
          class="form-control"
          name="email"
          required autocomplete="email"
          autofocus >
        </div>
      </div>

      <div class="form-group row">
        <label for="name" class="col-md-3 col-form-label text-md-right">Password<span class="required"><font color="red">*</font></span></label>
        <div class="col-md-9">
          <input id="name"
          type="text"
          class="form-control"
          name="password"
          required autocomplete="name"
          autofocus >
        </div>
      </div>
      
      <div class="form-group row">
        <label for="name" class="col-md-3 col-form-label text-md-right">Roles<span class="required"></span></label>
        <div class="col-md-9">
          <div class="input-group mb-3">
            <select class="custom-select" name="role" form="userCreate">
              <option value="" selected >.....</option>
              @foreach($roles as $role)
              <option value="{{$role->name}}">{{$role->name}}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>

      <div class="form-group row mb-0">
        <div class="col-md-6 offset-md-4">
          <button type="submit" class="btn btn-primary">
            Register
          </button>
        </div>
      </div>
    </form>


    @stop
