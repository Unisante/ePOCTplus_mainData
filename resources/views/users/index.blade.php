@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
@stop

@section('content')



<div class="col-md-9 col-lg-12 col-sm-12 pull-left" style="background: white;">
  <div class="panel-body">
  <form action="/users" method="get">
  <a class="pull-left btn btn-success" href="users/create">Register New User</a>
  <H3 align="center">
  <b>Users Information</b></H3>
    {{ csrf_field() }}
    <div class="input-group">
        <input type="text" class="form-control"  name="Search"
            placeholder="Search"> <span class="input-group-btn">
            <button type="submit" class="btn btn-default" name="find">
                <span class="glyphicon glyphicon-search"></span>
            </button>
        </span>
    </div>
</form>

<table class="table table-bordered table-striped" >
<thead>
  <tr>
    <th>ID</th>
    <th>Full Name</th>
    <th>Email</th>
    <th>Roles</th>
    <th>Action</th>

  </tr>
  </thead>
  <tbody>
    @foreach($users as $user)
      <tr>
        <td>{{$user->id}}</td>
        <td>{{$user->name}}</td>
        <td>{{$user->email}}</td>
        <td>
          @if(!empty($user->getRoleNames()))
          @foreach($user->getRoleNames() as $v)
             <label class="badge badge-success">{{ $v }}</label>
          @endforeach
          @else
          <label class="badge badge-success">Not Assigned</label>
          @endif
        </td>
        <td><a class="pull-center btn btn-primary btn-sm" href="/users/{{$user->id}}/edit" role="button">Edit</a>
        <a class="pull-center btn btn-primary btn-sm" href="/users/{{$user->id}}" role="button">View</a>
        </td>

      </tr>
      @endforeach
  </tbody>
</table>


</div>
</div>

@stop
