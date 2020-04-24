@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
@stop

@section('content')



<div class="col-md-9 col-lg-12 col-sm-12 pull-left" style="background: white;">
  <div class="panel-body">
    <div class="col-md-12">
      {{Form::open(['route' => ['user.index']])}}
      <a class="pull-left btn btn-success" href="{{route('user.create')}}">Register New User</a>
      <H3 align="center"><b>Users Information</b></H3>
      <div class="input-group">
        {{Form::submit('search', array('class' => 'btn btn-outline-secondary'))}}
        <div class="col-md-11">
          {{Form::text('find', '', array('class' => 'form-control pull-right'))}}
        </div>
      </div>
      {{ Form::close() }}
    </div>
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <td>SN</td>
          <th>ID</th>
          <th>Full Name</th>
          <th>Email</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach($users as $user)
        <tr>
          <td>{{$loop->index}}</td>
          <td>{{$user->id}}</td>
          <td>{{$user->name}}</td>
          <td>{{$user->email}}</td>
        <td><a class="pull-center btn btn-primary btn-sm" href="{{route('user.edit',[$user->id])}}" role="button">Edit</a>
            <a class="pull-center btn btn-primary btn-sm" href="#" role="button">View</a>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>


  </div>
</div>

@stop
