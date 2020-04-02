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

<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header"><button class="btn btn-outline-dark float-right" onclick="comparePatients()"> Compare Duplicates</button></div>
        <div class="card-body">
          @if (session('status'))
          <div class="alert alert-success" role="alert">
            {{ session('status') }}
          </div>
          @endif
          <div class="row justify-content-center">
            @include('layouts.compareModal')
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <button class="btn btn-outline-secondary" type="button" onclick="search()" >Search</button>
              </div>
              <select class="custom-select" id="search" onchange="search()" >
                <option selected>...</option>
                <option value="first_name">By First Name</option>
                <option value="last_name">By Last Name</option>
                {{-- <option value="date_of_birth">By Date Of Birth </option>
                <option value="natioal_id">By National Id</option> --}}
              </select>
             </div>
          </div>
          <div class="row justify-content-center mt-3">
            <div class="col">
              <table class="table table-hover table-bordered">
                <thead class="thead-light">
                  <th scope="col">Sn</th>
                  <th scope="col">First Name</th>
                  <th scope="col">Last Name</th>
                  <th scope="col">Actions</th>
                </thead>
                <tbody>
                  @foreach($catchEachDuplicate as $relativeDuplicates)
                    <tr class="table-secondary"><td>For The {{ $loop->index + 1 }}'s Duplicate<td></tr>
                    @foreach($relativeDuplicates as $duplicate)
                      <tr>
                        <th scope="row">{{ $loop->index + 1 }}</th>
                        <td>{{$duplicate->first_name}}</td>
                        <td>{{$duplicate->last_name}}</td>
                        <td><input type="checkbox" class="messageCheckbox" value="{{$duplicate->id}}"></td>
                      </tr>
                    @endforeach
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@stop
