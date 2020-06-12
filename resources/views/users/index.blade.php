@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
@stop

@section('content')



<div class="col-md-9 col-lg-12 col-sm-12 pull-left" style="background: white;">
  <div class="panel-body">
  <form action="/users" method="get">
  <a class="pull-left btn btn-outline-success" href="users/create">Register New User</a>
@if ($message = Session::get('success'))
<div class="alert alert-success">
  <p>{{ $message }}</p>
</div>
@endif
@if ($message = Session::get('error'))
<div class="alert alert-danger">
  <p>{{ $message }}</p>
</div>
@endif
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
             <label class="badge badge-info">{{ $v }}</label>
          @endforeach
          @else
          <label class="badge badge-warning">Not Assigned</label>
          @endif
        </td>
        <td><a class="pull-center btn btn-outline-info btn-sm" href="/users/{{$user->id}}/edit" role="button">Edit</a>
        <a class="pull-center btn btn-outline-info btn-sm" href="/users/{{$user->id}}" role="button">View</a>

        {{-- delete functionality starts --}}
        <!-- Button trigger modal -->
      <button type="button" class="btn btn-outline-danger" onclick="callModalWithId({{$user->id}})"
        >
        Delete User
      </button>

      <!-- Modal -->
      <div class="modal fade" id="deleteRole" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              Are You sure ?
            </div>
            <form id="deleteForm" action="/users" method="POST">
              <input name="_method" type="hidden" value="DELETE">
              {{ csrf_field() }}
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Delete</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">cancel</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      {{-- delete ends and reset begins --}}
      <button type="button" class="btn btn-outline-danger" onclick="callModalWithResetId({{$user->id}})"
        >
        Reset Password
      </button>
      <!-- Modal -->
      <div class="modal fade" id="resetRole" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              Are You sure ?
            </div>
            <form id="resetForm" action="/users" method="POST">
              {{ csrf_field() }}
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Reset</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">cancel</button>
              </div>
            </form>
          </div>
        </div>
      </div>
        </td>
      </tr>
      @endforeach
  </tbody>
</table>


</div>
</div>

<script>
  function callModalWithId(id){
    $("#deleteRole").modal()
    $('#deleteForm').attr('action', `users/${id}`);
  }
  function callModalWithResetId(id){
    $("#resetRole").modal()
    $('#resetForm').attr('action', `user/reset/${id}`);
  }
  </script>
@stop
