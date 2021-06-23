@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
@stop

@section('content')

@include('partials.errors')
@include('partials.success')

<div class="col-md-9 col-lg-9 col-sm-9 pull-left" style="background: white;">

<!-- Example row of columns -->
<h3 align="center">Edit Health Facility </h3>
    <div class="row col-sm-12 col-md-12 col-lg-12" style="background:white; margin: 10px">
    <form method="GET" action="/update_profile">
        {{csrf_field()}}

        <!-- Name -->
        <div class="form-group row">
        <label for="name" class="col-md-2 col-form-label text-md-right">Name<span class="required">*</span></label>
        <div class="col-md-6">
            <input id="name" 
                    type="text" 
                    class="form-control @error('name') is-invalid @enderror" 
                    name="name" 
                    value="{{$facility->name}}" 
                    required autocomplete="name" 
                    autofocus >
            @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        </div>


        <!-- country -->
        <div class="form-group row">
        <label for="country" class="col-md-2 col-form-label text-md-right">Country<span class="required">*</span></label>
        <div class="col-md-6">
            <input id="country" 
                    type="text" 
                    class="form-control @error('country') is-invalid @enderror" 
                    name="country" 
                    value="{{$facility->country}}" 
                    required autocomplete="country" 
                    autofocus >
            @error('country')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        </div>

        <!-- XXX -->
        <div class="form-group row">
        <label for="XXX" class="col-md-2 col-form-label text-md-right">XXX<span class="required">*</span></label>
        <div class="col-md-6">
            <input id="XXX" 
                    type="text" 
                    class="form-control @error('XXX') is-invalid @enderror" 
                    name="XXX" 
                    value="{{$facility->XXX}}" 
                    required autocomplete="XXX" 
                    autofocus >
            @error('XXX')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        </div>

        <div class="form-group row">
        <label for="email" class="col-md-2 col-form-label text-md-right">{{ __('E-Mail Address') }}<span class="required">*</span></label>
        <div class="col-md-6">
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{$users->email}}" required autocomplete="email">

            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        </div>

        <div class="form-group row mb-0">
        <div class="col-md-6 offset-md-4">
            <button type="submit" class="btn btn-success">Save</button>
    </form>  
@stop