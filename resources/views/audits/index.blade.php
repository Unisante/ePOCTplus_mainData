@extends('adminlte::page')

<link href="{{ asset('css/datatable.css') }}" rel="stylesheet">

@section('content')
<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header d-flex">
          <span><h3>Audits</h3></span>
        </div>
        <div class="card-body">
          @if (session('status'))
          <div class="alert alert-success" role="alert">
            {{ session('status') }}
          </div>
          @endif
          @include('layouts.compareModal')
          @include('layouts.datatable')
          <div class="row">
            <div class="col-md-12">
              @if(count($audits) > 0)
              <table class="table" style="table-layout:fixed;width:100%;">
                <thead>
                  <tr>
                    <th scope="col" style="width:10%;">Id</th>
                    <th scope="col" style="width:15%;">Auditable type</th>
                    <th scope="col" style="width:10%;">Auditable id</th>
                    <th scope="col" style="width:50%;">Old values</th>
                    <th scope="col" style="width:50%;">New values</th>
                    <th scope="col" style="width:20%;">Created at</th>
                    <th scope="col" style="width:20%;">Updated at</th>
                    <th scope="col" style="width:20%;">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($audits as $audit)
                  <tr>
                    <td>{{ $audit->id }}</td>
                    <td>{{ $audit->auditable_type }}</td>
                    <td>{{ $audit->auditable_id }}</td>
                    <td>{{ $audit->old_values }}</td>
                    <td>{{ $audit->new_values }}</td>
                    <td>{{ $audit->created_at }}</td>
                    <td>{{ $audit->updated_at }}</td>
                    <td><a href="{{route('audits.show',[$audit->id])}}" class="btn btn-outline-dark"> Show Audit</a></td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
              @else
              <p>No audits found</p>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@stop


