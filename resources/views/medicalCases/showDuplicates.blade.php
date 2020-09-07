@extends('adminlte::page')

@section('content')

<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header"><button class="btn btn-outline-dark float-right" onclick="compareMedicalCases()">Merge Duplicates</button></div>
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
                    <p id="display">You want to delete MedicalCase id <span id="setId1"></span> ?</p>
                  </div>
                  <div class="modal-footer">
                    <form action="{{route('MedicalCasesController@destroy')}}" method="POST">
                      @csrf
                      <input id="medicalc_id" type="text" name="medicalc_id"  hidden>
                      <button type="submit" class="btn btn-primary" >Save changes</button>
                    </form>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  </div>

                </div>
              </div>
            </div>
          </div>
          <div class="row justify-content-center mt-3">
            <div class="col-md-10">
              <form action="{{route('MedicalCasesController@searchDuplicates')}}" method="POST" id="searchform" class="input-group mb-3">
                @csrf
                <div class="input-group-prepend">
                  <button class="btn btn-outline-secondary" type="Submit">Search</button>
                </div>
                <select class="custom-select" name="search" form="searchform">
                  <option selected>Choose...</option>
                  <option value="patient_id">Patient Id</option>
                  <option value="version_id">Version Id</option>
                </select>
              </form>
              @if($catchEachDuplicate)
              <table class="table table-hover table-bordered">
                <thead class="thead-light">
                  <th scope="col">Sn</th>
                  <th scope="col">Medical Case Id</th>
                  <th scope="col">Patient Id</th>
                  <th scope="col">Version Id</th>
                  <th scope="col">Created At</th>
                  <th scope="col">Actions</th>
                </thead>
                <tbody>
                  @foreach($catchEachDuplicate as $relativeDuplicates)
                  <tr class="table-secondary"><td>For The {{ $loop->index + 1 }}'s Duplicate<td></tr>
                    @foreach($relativeDuplicates as $duplicate)
                    <tr>
                      <th scope="row">{{ $loop->index + 1 }}</th>
                      <td>{{$duplicate->id}}</td>
                      <td>{{$duplicate->patient_id}}</td>
                      <td>{{$duplicate->version_id}}</td>
                      <td>{{$duplicate->created_at}}</td>
                      <td>
                        <input type="checkbox" class="messageCheckbox" value="{{$duplicate->id}}">
                        <a  class="btn btn-outline-dark" data-toggle="modal" data-target="#deleteRow" onclick="takeId({{$duplicate->id}})">Drop This Row</a>
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
