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
  .dataTables_filter input{
    border: 1px solid #ddd !important;
  }
  #the_card{
    display: flex;
    align-items: center;
    justify-content: center;
  }
</style>
@stop

@section('content')
<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
        <a href="/medicalCases/{{$medicalCaseId}}" class="btn btn-outline-dark"> Back</a>
        </div>
        <div class="card-body">
          @if (session('status'))
          <div class="alert alert-success" role="alert">
            {{ session('status') }}
          </div>
          @endif
          <div class="row">
            @include('layouts.compareModal')
            <div class="col-md-10 offset-md-1">

              @if($allAudits)

              <table class="table">
                <thead>
                  <th scope="col">SN</th>
                  <th scope="col">user</th>
                  <th scope="col">question</th>
                  <th scope="col">old value</th>
                  <th scope="col">new value</th>
                  <th scope="col">event</th>
                  <th scope="col">ip address</th>
                  <th scope="col">created_at</th>
                </thead>
                <tbody>
                  @foreach($allAudits as $audit)
                  <tr>
                    <th scope="row">{{ $loop->index }}</th>
                    <th class="mr-5">{{$audit['user']}}</th>
                    <th>{{$audit['question']}}</th>
                    <th>{{$audit['old_value']}}</th>
                    <th>{{$audit['new_value']}}</th>
                    <th>{{$audit['event']}}</th>
                    <th>{{$audit['ip_address']}}</th>
                    <th>{{$audit['created_at']}}</th>
                  </tr>
                  @endforeach
                </tbody>
              </table>
              @else
                <div id="the_card">
                  <h2>There are no changes to this medical Case</h2>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@stop
