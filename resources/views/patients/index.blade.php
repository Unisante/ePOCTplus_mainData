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


<link  href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js" defer></script>

<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">Patients</div>
        <div class="card-body">
          @if (session('status'))
          <div class="alert alert-success" role="alert">
            {{ session('status') }}
          </div>
          @endif
          <div class="row">
            <div class="col-md-8 offset-md-2">
              @if(count($patients)>0)
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">SN</th>
                    <th scope="col">First Name</th>
                    <th scope="col">Last Name</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($patients as $patient)
                  <tr>
                    <th scope="row">{{ $loop->index }}</th>
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
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@stop
