@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
@stop

@section('content')

@include('partials.errors')
@include('partials.success')

<div class="col-md-9 col-lg-9 col-sm-9 pull-left" style="background: white;">

<!-- Example row of columns -->
<h3 align="center">Edit {{$name}} </h3>
    <div class="row col-sm-12 col-md-12 col-lg-12" style="background:white; margin: 10px">
    <form method="post" action="{{route($url . '.update',$instance->id)}}">
        {{csrf_field()}}
        {{ method_field('PUT') }}
        @foreach ($inputs as $key => $value)
            <div class="form-group row">
            <label for={{$value}} class="col-md-3 col-form-label text-md-right">{{$key}}<span class="required">*</span></label>
            <div class="col-md-6">
                <input id={{$value}} 
                        type="text" 
                        class="form-control"
                        name={{$value}} 
                        value={{$instance[$value]}}
                        required autocomplete={{$value}} 
                        autofocus >
            </div>
            </div>
        @endforeach
        <div class="form-group row mb-0">
        <div class="col-md-6 offset-md-4">
            <button type="submit" class="btn btn-success">Save</button>
        </div>
        </div>
    </form>
    </div>  
@stop