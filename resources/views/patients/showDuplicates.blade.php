@extends('adminlte::page')

@section('content')
<link href="{{ asset('css/checkbox.css') }}" rel="stylesheet">
<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header"><button class="btn btn-outline-dark float-right" onclick="mergePatients()">Compare Cases</button></div>
        <div class="card-body">
          @if (session('status'))
          <div class="alert alert-success" role="alert">
            {{ session('status') }}
          </div>
          @endif
          <div class="row justify-content-center">
            @include('layouts.compareModal')
            <div class="modal" tabindex="-1" id="deleteRow" role="dialog">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Delete Row</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <p id="display">You want to delete patient id <span id="setId1"></span> ?</p>
                  </div>
                  <div class="modal-footer">
                  <form action="{{route('PatientsController@destroy')}}" method="POST">
                      @csrf
                      <input id="patient_id" type="text" name="patient_id"  hidden>
                      <button type="submit" class="btn btn-primary" >Save changes</button>
                    </form>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row justify-content-center">
            <div class="col-md-12">
              <form action="{{route('PatientsController@searchDuplicates')}}" method="POST" id="searchform" >
                @csrf
                <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-8">
                  <div class="d-flex justify-content-between">
                  <div class="form-check">
                    <label class="container">
                      <input type="checkbox" class="form-check-input" name="searchCriteria[]" value="first_name">First Name
                      <span class="checkmark"></span>
                    </label>
                  </div>
                  <div class="form-check">
                    <label class="container">
                      <input type="checkbox" class="form-check-input" name="searchCriteria[]" value="last_name">Last Name
                      <span class="checkmark"></span>
                    </label>
                  </div>
                  <div class="form-check">
                    <label class="container">
                      <input type="checkbox" class="form-check-input" name="searchCriteria[]" value="birthdate">DOB
                      <span class="checkmark"></span>
                    </label>
                  </div>
                  <div class="form-check">
                    <button class="btn btn-outline-secondary float-right" type="Submit">Search</button>
                  </div>
                </div>
                </div>
              </div>
              </form>
              @if($catchEachDuplicate)
              <table class="table table-hover table-bordered">
                <thead class="thead-light">
                  <th scope="col">Sn</th>
                  <th scope="col">Patient Id</th>
                  <th scope="col">First Name</th>
                  <th scope="col">Last Name</th>
                  <th scope="col">BirthDate</th>
                  <th scope="col">created_at</th>
                  <th scope="col">Actions</th>
                </thead>
                <tbody>
              @foreach($catchEachDuplicate as $relativeDuplicates)
                  <tr class="table-secondary"><td>For The {{ $loop->index + 1 }}'s Duplicate<td></tr>
                @foreach($relativeDuplicates as $duplicate)
                <tr>
                  <th scope="row">{{ $loop->index + 1 }}</th>
                  <td>{{$duplicate['id']}}</td>
                  <td>{{$duplicate['first_name']}}</td>
                  <td>{{$duplicate['last_name']}}</td>
                  <td>{{$duplicate['birthdate']}}</td>
                  <td>{{$duplicate['created_at']}}</td>
                  <td>
                    <div id="action">
                      <label class="container">
                      <input type="checkbox" class="messageCheckbox" value="{{$duplicate['id']}}">
                      <span class="checkmark"></span>
                      </label>
                      {{-- <a  class="btn btn-outline-dark" data-toggle="modal" data-target="#deleteRow" onclick="takeId({{$duplicate['id']}})">Drop This Row</a> --}}
                    </div>
                  </td>
                </tr>
                @endforeach
              @endforeach
            </tbody>
          </table>
                @else
                <h2>There are no Duplicates</h2>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  @stop


