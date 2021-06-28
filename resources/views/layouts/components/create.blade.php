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
    @foreach ($inputs as $key => $input)
    <div class="form-group row">
        <label for={{ $input['keyword'] }} class="col-md-4 col-form-label text-md-left">{{$key}}</label>
        
        @if ($input['type'] == 'dropdown')
        <div class="col-md-9">
        <select class="form-control" name={{$input['keyword']}} id={{$input['keyword']}}>
        @foreach ($input['options'] as $option)
        <option value={{$option}}>{{$option}}</option>
        @endforeach
        </select> 
        </div>
        @endif

        @if ($input['type'] == 'text')
        <div class="col-md-9">
          <input id={{ $input['keyword'] }}
          type="text"
          class="form-control"
          name={{ $input['keyword'] }}
          required autocomplete={{ $input['keyword'] }}
          autofocus >
        </div>
        @endif
       
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
