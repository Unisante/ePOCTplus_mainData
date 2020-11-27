@extends('adminlte::page')
@section('content')
<div class="row">
  <div class="col-lg-12 margin-tb">
    <div class="pull-left">
      <h2>Edit Role</h2>
    </div>
    <div class="pull-right">
      <a class="btn btn-primary" href="{{ route('roles.index') }}"> Back</a>
    </div>
  </div>
</div>


@if (count($errors) > 0)
<div class="alert alert-danger">
  <strong>Whoops!</strong> There were some problems with your input.<br><br>
  <ul>
    @foreach ($errors->all() as $error)
    <li>{{ $error }}</li>
    @endforeach
  </ul>
</div>
@endif


<form method="POST" action="/role/removePerm/{{$role->id}}" accept-charset="UTF-8">
  @csrf
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
      <div class="form-group">
        <strong>Name:</strong>
        <input type="text" name="name" class="form-control" value="{{ old('name', optional($role)->name) }}"
      </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
      <div class="form-group">
        <strong>Permission:</strong>
        <br/>
        <select data-placeholder="Select Permissions" class="form-control tagsselector" name="permission[]" multiple="multiple">
          @foreach($permissions as $value)
          <option value="{{ $value->name }}"  {{ $role->permissions->contains($value->id) ? 'selected' : '' }}>{{ $value->name }}</option>
          <br/>
          @endforeach

        </select>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-6 col-sm-6 col-md-6 text-center">
        <button type="submit" class="btn btn-primary">Remove</button>
      </div>
    </div>
  </div>
</form>
@endsection
