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
        <div class="card-body">
          @if (session('status'))
          <div class="alert alert-success" role="alert">
            {{ session('status') }}
          </div>
          @endif
          <div class="row justify-content-center">
              <div class="col-md-4">
                <a href="/medicalCase/duplicates/patient_id" class="btn btn-outline-secondary">By Patient Id</a>
                <a href="/medicalCase/duplicates/created_at" class="btn btn-outline-secondary">By Created At</a>
              </div>
              <div class="col-md-4">
                <a href="/medicalCase/duplicates/version_id" class="btn btn-outline-secondary">By Version Id </a>
                <a href="" class="btn btn-outline-secondary">By National Id</a>
              </div>
          </div>
          {{-- @include('layouts.compareModal') --}}
          {{-- @if(count($catchEachDuplicate)) --}}
          <div class="row justify-content-center mt-3">
            <div class="col">
              <table class="table table-hover table-bordered">
                <thead class="thead-light">
                  <th scope="col">Sn</th>
                  <th scope="col">Patient Name</th>
                  <th scope="col">Case Answers</th>
                  <th scope="col">Created At</th>
                  <th scope="col">Version Id</th>
                  <th scope="col">Actions</th>
                </thead>
                <tbody>
                  @foreach($catchEachDuplicate as $relativeDuplicates)
                  <tr class="table-secondary"><td>For The {{ $loop->index + 1 }}'s Duplicate<td></tr>

                  @foreach($relativeDuplicates as $duplicate)
                  <tr>
                  <th scope="row">{{ $loop->index + 1 }}</th>
                  <td>{{$duplicate->patient->first_name}} {{$duplicate->patient->last_name}}</td>
                  <td>{{$duplicate->medical_case_answers->count()}}</td>
                  <td>{{$duplicate->created_at}}</td>
                  <td>{{$duplicate->version_id}}</td>
                  <td><input type="checkbox" class="messageCheckbox" value="{{$duplicate->id}}"></td>
                  </tr>
                  @endforeach
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          {{-- @else

          @endif --}}
        </div>
      </div>
    </div>
  </div>
</div>
@stop
