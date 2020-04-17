@extends('adminlte::page')
@section('content')

@include('partials.errors')
@include('partials.success')

<div class="col-md-9 col-lg-9 col-sm-9 pull-left" style="background: white;">
<<<<<<< HEAD
  <h3 align="center">Register New User </h3>
  <!-- Example row of columns -->
  <div class="row col-sm-12 col-md-12 col-lg-12" style="background:white; margin: 10px">
    <form action="{{route('users.store')}}" method="post">
      {!! csrf_field() !!}
      <div class="form-group row">
        <label for="name" class="col-md-3 col-form-label text-md-right">Full Name<span class="required"><font color="red">*</font></span></label>
        <div class="col-md-9">
          <input id="name"
          type="text"
          class="form-control @error('name') is-invalid @enderror"
          name="name"
          value="{{ old('name') }}"
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
        <label for="email" class="col-md-3 col-form-label text-md-right">E-mail Address<span class="required"><font color="red">*</font></span></label>
        <div class="col-md-9">
          <input id="email"
          type="email"
          class="form-control @error('email') is-invalid @enderror"
          name="email"
          value="{{ old('email') }}"
          required autocomplete="email"
          autofocus >
          @error('email')
          <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
          </span>
          @enderror
        </div>
      </div>

      <div class="form-group row">
        <label for="name" class="col-md-3 col-form-label text-md-right">Password<span class="required"><font color="red">*</font></span></label>
        <div class="col-md-9">
          <input id="name"
          type="text"
          class="form-control @error('name') is-invalid @enderror"
          name="name"
          value="{{ old('name') }}"
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
        <label for="name" class="col-md-3 col-form-label text-md-right">Password<span class="required"><font color="red">*</font></span></label>
        <div class="col-md-9">
          <input id="name"
          type="text"
          class="form-control @error('name') is-invalid @enderror"
          name="name"
          value="{{ old('name') }}"
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
        <label for="name" class="col-md-3 col-form-label text-md-right">Role<span class="required"><font color="red">*</font></span></label>
        <div class="col-md-9">
          <input id="name"
          type="text"
          class="form-control @error('name') is-invalid @enderror"
          name="name"
          value="{{ old('name') }}"
          required autocomplete="name"
          autofocus >
          @error('name')
          <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
          </span>
          @enderror
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
=======
<h3 align="center">Register New User </h3>
<!-- Example row of columns -->
<div class="row col-sm-12 col-md-12 col-lg-12" style="background:white; margin: 10px">
            <form action="{{route('user.store')}}" method="post">
                {!! csrf_field() !!}

                <div class="form-group row">
                            <label for="name" class="col-md-3 col-form-label text-md-right">Full Name<span class="required"><font color="red">*</font></span></label>
                            <div class="col-md-9">
                                <input id="name"
                                       type="text"
                                       class="form-control @error('name') is-invalid @enderror"
                                       name="name"
                                       value="{{ old('name') }}"
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
                            <label for="email" class="col-md-3 col-form-label text-md-right">E-mail Address<span class="required"><font color="red">*</font></span></label>
                            <div class="col-md-9">
                                <input id="email"
                                       type="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       name="email"
                                       value="{{ old('email') }}"
                                       {{-- required autocomplete="email" --}}
                                       autofocus >
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password" class="col-md-3 col-form-label text-md-right">Password<span class="required"><font color="red">*</font></span></label>
                            <div class="col-md-9">
                                <input id="password"
                                       type="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       name="password"
                                       value="{{ old('password') }}"
                                       required autocomplete="password"
                                       autofocus >
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
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
>>>>>>> list-medical-cases


    @stop
