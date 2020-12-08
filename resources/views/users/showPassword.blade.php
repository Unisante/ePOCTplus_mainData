@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
@stop

@section('content')

@include('partials.errors')
@include('partials.success')

<div class="col-md-9 col-lg-9 col-sm-9 pull-left" style="background: white;">
  <!-- Example row of columns -->
  <h3 align="center">Change Password</h3>
  <div class="row col-sm-12 col-md-12 col-lg-12" pull-center style="background:white; margin: 10px">
    <div class="col-md-8 offset-md-2">
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
      <div class="form-group row">
        <label for="email" class="col-md-3 col-form-label text-md-right">Role <span class="required"></span></label>
        <div class="col-md-9">
          @foreach($user->roles as $role)
          <input id="role" type="text" disabled class="form-control @error('email') is-invalid @enderror" name="email" value="{{$role->name}}" required autocomplete="email">
          @endforeach
        </div>
      </div>
    </div>

    <div class="col-md-8 offset-md-2">
      <form action="{{route('UsersController@changePassword')}}" method="POST" id="passwordChange">
        {!! csrf_field() !!}
        <div class="form-group row">
          <label for="name" class="col-md-5 col-form-label text-md-right">Enter Current Password<span class="required"></span></label>
          <div class="col-md-7">
            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="current_password" required>
          </div>
        </div>
        <div class="form-group row">
          <label for="name" class="col-md-5 col-form-label text-md-right">Enter New Password<span class="required"></span></label>
          <div class="col-md-7">
            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="new_password" required>
          </div>
        </div>
        <div class="form-group row justify-content-center">
          <button type="submit" class="btn btn-outline-secondary">Change Password</button>
        </div>
      </form>
    </div>

  </div>
  @stop
