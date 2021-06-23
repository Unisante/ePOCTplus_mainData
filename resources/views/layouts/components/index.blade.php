@include('partials.errors')
@include('partials.success')

<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-12">
    <a class="pull-left btn btn-outline-success" href="{{route('health-facilities.create')}}">Create New {{$singName}}</a>
      <div class="card">
      
        <div class="card-header d-flex ">
          <span>
            <h3>{{$plurName}}</h3>
          </span>
        </div>
        <div class="card-body">
          @if (session('status'))
          <div class="alert alert-success" role="alert">
            {{ session('status') }}
          </div>
          @endif
          @include('layouts.datatable')
          <div class="row">
            <div class="col-md-10 offset-md-1">
              @if(count($instances)>0)
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">SN</th>
                    @foreach($columns as $col)
                    <th scope="col">{{$col}}</th>
                    @endforeach
                    <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($instances as $instance)
                  <tr>
                    <th scope="row">{{ $loop->index+1 }}</th>
                    @foreach($attributes as $attr)
                    <td>{{$instance[$attr]}}</td>
                    @endforeach
                    <td>
                      <a class="pull-center btn btn-outline-info btn-sm" href="{{route($url . '.edit',$instance->id)}}" role="button">Edit</a>
                      <a class="pull-center btn btn-outline-info btn-sm" href="{{route($url . '.show',$instance->id)}}" role="button">View</a>

                        {{-- delete functionality starts --}}
                        <!-- Button trigger modal -->
                      <button type="button" class="btn btn-outline-danger" onclick="callModalWithId({{$instance->id}})">Delete</button>   
                    </td>
                  </tr>
                  @endforeach
                </tbody>
                @else
                <span>
                  <h3>No {{$plurName}} Have been Created</h3>
                </span>
                @endif
            </div>
          </div>
        </div>
      </div>
    
    </div>
  </div>
</div>


<!-- Modal -->
<div class="modal fade" id="deleteRole" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Delete {{$singName}} </h5>
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
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>


<script>
function callModalWithId(id){
  $("#deleteRole").modal()
  $('#deleteForm').attr('action', `health-facilities/${id}`);
}
</script>