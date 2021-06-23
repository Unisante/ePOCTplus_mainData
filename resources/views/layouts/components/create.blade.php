@extends('adminlte::page')
@section('content')

@include('partials.errors')
@include('partials.success')

<div class="col-md-9 col-lg-9 col-sm-9 pull-left" style="background: white;">
  <h3 align="center">Create New {{$name}}</h3>
  <!-- Example row of columns -->
  <div class="row col-sm-12 col-md-12 col-lg-12" style="background:white; margin: 10px">
    <form action="{{route( $url . '.store')}}" method="post" id="userCreate">
      {!! csrf_field() !!}
    @foreach ($inputs as $key => $value)
    <div class="form-group row">
        <label for={{ $value }} class="col-md-3 col-form-label text-md-right">{{$key}}<span class="required"><font color="red">*</font></span></label>
        <div class="col-md-9">
          <input id={{ $value }}
          type="text"
          class="form-control"
          name={{ $value }}
          required autocomplete={{ $value }}
          autofocus >
        </div>
    </div>
    @endforeach
      <div class="form-group row mb-0">
        <div class="col-md-6 offset-md-4">
          <button type="submit" class="btn btn-primary">
            Create
          </button>
        </div>
      </div>
    </form>
</div>
</div>
<!--
    @if($errors->any()){{ implode('', $errors->all()) }}@endif
    @stop
    -->
