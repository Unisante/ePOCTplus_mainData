@extends('adminlte::page')

@section('content')
<div class="row">
  <div class="col-lg-12 margin-tb">
    <div class="pull-left">
      <h2>Role Management</h2>
    </div>
    <div class="pull-right">
      <a class="btn btn-outline-success" href="{{ route('roles.create') }}"> Create New Role</a>
    </div>
  </div>
</div>


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


<table class="table table-bordered">
  <tr>
    <th>No</th>
    <th>Name</th>
    <th>Action</th>
  </tr>
  @foreach ($roles as $key => $role)
  <tr>
    <td>{{ ++$i }}</td>
    <td>{{ $role->name }}</td>
    <td>
      <a class="btn btn-outline-info" href="{{ route('roles.show',$role->id) }}">Show Permissions</a>
      <a class="btn btn-outline-info" href="roles/removeRole/{{$role->id}}">Remove Permission</a>
      <a class="btn btn-outline-info" href="{{ route('roles.edit',$role->id) }}">Update Permission</a>

      <!-- Button trigger modal -->
      <button type="button" class="btn btn-outline-danger" onclick="callModalWithId({{$role->id}})"
        >
        Delete Role
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
            <form id="deleteForm" action="{{ route('roles.destroy',$role->id) }}" method="POST">
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
    </td>
  </tr>
  @endforeach
</table>


{!! $roles->render() !!}

<script>
function callModalWithId(id){
  $("#deleteRole").modal()
  $('#deleteForm').attr('action', `roles/${id}`);
}
</script>


@endsection
