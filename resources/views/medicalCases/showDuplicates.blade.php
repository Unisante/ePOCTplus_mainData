@extends('adminlte::page')

@section('content')

<div class="container-fluid">
  <div class="row justify-content-center">
    {{-- <div> --}}
    <div class="col-md-12">
      {{-- <div> --}}
      <div class="card">
        <div class="card-header"><button class="btn btn-outline-dark float-right" onclick="compareMedicalCases()">Compare Medical Cases</button></div>
        <div class="card-body">
          @if (session('status'))
          <div class="alert alert-success" role="alert">
            {{ session('status') }}
          </div>
          @endif
          <div class="row justify-content-center">
            @include('layouts.compareModal')
            <div class="modal" tabindex="-1" id="markRow" role="dialog">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Mark Medical Case</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <p id="display">You want to mark this MedicalCase?.This will inturn remove its follow up from Redcap  </p>
                  </div>
                  <div class="modal-footer">
                    <form action="{{route('MedicalCasesController@deduplicate_redcap')}}" method="POST">
                      @csrf
                      <input id="medicalcase_id" type="text" name="medicalc_id"  hidden>
                      <button type="submit" class="btn btn-primary" >Save changes</button>
                    </form>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  </div>

                </div>
              </div>
            </div>
          </div>
          <div class="row justify-content-center mt-3">
            <div class="col-md-12">
              {{-- <form action="{{route('MedicalCasesController@searchDuplicates')}}" method="POST" id="searchform" class="input-group mb-3">
                @csrf
                <div class="input-group-prepend">
                  <button class="btn btn-outline-secondary" type="Submit">Search</button>
                </div>
                <select class="custom-select" name="search" form="searchform">
                  <option selected>Choose...</option>
                  <option value="patient_id">Patient Id</option>
                  <option value="version_id">Version Id</option>
                </select>
              </form> --}}
              @if($catchEachDuplicate)
              <table class="table table-hover table-bordered">
                <thead class="thead-light">
                  <th scope="col">Sn</th>
                  {{-- <th scope="col">Medical Case Id</th> --}}
                  <th scope="col">local_medical_case_id</th>
                  <th scope="col">local_patient_id</th>
                  <th scope="col">Consultation Date</th>
                  {{-- <th scope="col">caregiver Phone</th> --}}
                  {{-- <th scope="col">Refer Followup consultation</th> --}}
                  <th scope="col">Mark</th>
                  <th scope="col">Flag</th>
                </thead>
                <tbody>
                  @foreach($catchEachDuplicate as $relativeDuplicates)
                  <tr class="table-secondary"><td>For The {{ $loop->index + 1 }}'s Duplicate<td></tr>
                    @foreach($relativeDuplicates as $duplicate)
                    <tr>
                      <th scope="row">{{ $loop->index + 1 }}</th>
                      {{-- <td>{{$duplicate->id}}</td> --}}
                      <td>{{$duplicate->local_medical_case_id}}</td>
                      <td>{{$duplicate->patient->local_patient_id}}</td>
                      <td>{{$duplicate->consultation_date}}</td>
                      {{-- <td>{{$duplicate->created_at}}</td> --}}
                      {{-- <td>Not yet done</td> --}}
                      <td>
                        <input type="checkbox" class="messageCheckbox" value="{{$duplicate->id}}">
                        </td>
                        <td>
                        <a  class="btn btn-outline-primary" data-toggle="modal" data-target="#markRow" onclick="takeCaseId({{$duplicate->id}})">Mark Duplicate</a>
                      </td>
                      {{-- <td>
                        <input type="checkbox" class="messageCheckbox" value="{{$duplicate->id}}">
                        <a  class="btn btn-outline-dark" data-toggle="modal" data-target="#deleteRow" onclick="takeId({{$duplicate->id}})">Drop This Row</a>
                      </td> --}}
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
