@extends('adminlte::page')



@section('content_header')
@stop

@section('content')
@include('partials.errors')
@include('partials.success')


<div class="col-md-9 col-lg-9 col-sm-9 pull-left" style="background: white;">
  <!-- Example row of columns -->
  <h3 align="center">Edit User </h3>
  <div class="row col-sm-12 col-md-12 col-lg-12" pull-center style="background:white; margin: 10px">
    <form method="post" action="{{route('users.update',[$user->id])}}" id="userEdit">

      {{csrf_field()}}
      <input type="hidden" name="_method" value="put">

      <div class="form-group row">
        <label for="name" class="col-md-3 col-form-label text-md-right">Full Name<span class="required"></span></label>
        <div class="col-md-9">
          <input id="name"
          type="text"
          class="form-control @error('name') is-invalid @enderror"
          name="name"
          value="{{$user->name}}"
          required autocomplete="name"
          autofocus >
          @error('name')
          <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
          </span>
          @enderror
        </div>
      </div>

      <div class="form-group row">
        <label for="email" class="col-md-3 col-form-label text-md-right">{{ __('E-Mail Address') }}<span class="required"></span></label>

        <div class="col-md-9">
          <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{$user->email}}" required autocomplete="email">

          @error('email')
          <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
          </span>
          @enderror
        </div>
      </div>

      <div class="form-group row">
        <label for="name" class="col-md-3 col-form-label text-md-right">Roles<span class="required"></span></label>
        <div class="col-md-9">
          <div class="input-group mb-3">
            <select class="custom-select" name="role" form="userEdit">
              @foreach($roles as $role)
              <option value="{{ $role->name }}"  {{ $user->roles->contains($role->id) ? 'selected' : '' }}>{{ $role->name }}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>

      <div class="form-group row mb-0">
        <div class="col-md-6 offset-md-4">
          <button type="submit" class="btn btn-primary">
            Update
          </button>
        </form>
      </div>
    </div>



    @stop
