@extends('adminlte::page')

@section('css')
<style type="text/css">
  .required::after {
    content: "*";
    color: red;
  }

  .small-text {
    font-size: small;
  }
</style>

@stop

@section('content')


<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js" defer></script>

<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header d-flex">
          <span>Patients</span>
          <button class="btn btn-outline-dark ml-auto p-2" onclick="compare()"> Compare</button>
        </div>
        <div class="card-body">
          @if (session('status'))
          <div class="alert alert-success" role="alert">
            {{ session('status') }}
          </div>
          @endif
          <div class="modal" tabindex="-1" id="modalCheckBox" role="dialog">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Modal title</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <p id="display">Compare between Two Patients</p>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-8 offset-md-2">
              @if(count($patients)>0)
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">SN</th>
                    <th>checkbox</th>
                    <th scope="col">First Name</th>
                    <th scope="col">Last Name</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($patients as $patient)
                  <tr>
                    <th scope="row">{{ $loop->index }}</th>
                    <th><input type="checkbox" class="messageCheckbox" value="{{$patient->id}}"></th>
                    <td>{{$patient->first_name}}</td>
                    <td>{{$patient->last_name}}</td>
                    <td><a href="/patient/{{$patient->id}}" class="btn btn-outline-dark"> Show Patient</a></td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
              @else
              <p>No Posts Found</p>
              @endif

              <script>
                $(document).ready( function () {
                  $('.table').DataTable();
                } )
              </script>
              <script>
                function compare() {
                  let checkedValue = [];
                  let inputElements = document.getElementsByClassName('messageCheckbox');
                  for(let i=0; inputElements[i]; ++i){
                        if(inputElements[i].checked){
                            checkedValue.push(inputElements[i].value)
                        }
                  }

                  if(!(checkedValue.length > 2) && !(checkedValue.length < 1)){
                    location.href = `/patients/compare/${checkedValue}`;
                  }else{
                    $("#modalCheckBox").modal('show');
                  }
                }
              </script>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@stop
