@extends('adminlte::page')

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
            <form action="/patients/merge" method="POST">
              @csrf
              <table class="table">
                <thead>
                  <th scope="col">Demographics</th>
                  <th scope="col">First Patient</th>
                  <th scope="col">Second Patient</th>
                </thead>
                <tbody>
                    <tr>
                      <td>Patient Id:</td>
                      <td>
                        <input type="radio" name="local_patient_id" value="{{$first_patient->local_patient_id}}" checked>
                        <label id="label_id">{{$first_patient->local_patient_id}}</label>
                        <div class="check"></div>
                      </td>
                      <td>
                        <input type="radio" name="local_patient_id" value="{{$second_patient->local_patient_id}}">
                        <label id="label_id">{{$second_patient->local_patient_id}}</label>
                        <div class="check"></div>
                      </td>
                    </tr>
                    <tr>
                      <td>First Name:</td>
                      <td>
                        <input type="radio"  name="first_name" value="{{$first_patient->first_name}}" checked>
                        <label id="label_id">{{$first_patient->first_name}}</label>
                        <div class="check"></div>
                      </td>
                      <td>
                        <input type="radio"  name="first_name" value="{{$second_patient->first_name}}">
                        <label id="label_id">{{$second_patient->first_name}}</label>
                        <div class="check"></div>
                      </td>
                    </tr>
                    <tr>
                      <td>Last Name:</td>
                      <td>
                        <input type="radio" name="last_name" value="{{$first_patient->last_name}}" checked>
                        <label id="label_id">{{$first_patient->last_name}}</label>
                        <div class="check"></div>
                      </td>
                      <td>
                        <input type="radio" name="last_name" value="{{$second_patient->last_name}}">
                        <label id="label_id">{{$second_patient->last_name}}</label>
                        <div class="check"></div>
                      </td>
                    </tr>
                    <tr>
                      <td>BirthDate:</td>
                      <td>
                        <input type="radio" name="birthdate" value="{{$first_patient->birthdate}}" checked>
                        <label id="label_id">{{$first_patient->birthdate}}</label>
                        <div class="check"></div>
                      </td>
                      <td>
                        <input type="radio" name="birthdate" value="{{$second_patient->birthdate}}">
                        <label id="label_id">{{$second_patient->birthdate}}</label>
                        <div class="check"></div>
                      </td>
                    </tr>
                    <tr>
                      <td>Weight:</td>
                      <td>
                        <input type="radio" name="weight" value="{{$first_patient->weight}}" checked>
                        <label id="label_id">{{$first_patient->weight}}</label>
                        <div class="check"></div>
                      </td>
                      <td>
                        <input type="radio" name="weight" value="{{$second_patient->weight}}">
                        <label id="label_id">{{$second_patient->weight}}</label>
                        <div class="check"></div>
                      </td>
                    </tr>
                    <tr>
                      <td>Gender:</td>
                      <td>
                        <input type="radio" name="gender" value="{{$first_patient->gender}}" checked>
                        <label id="label_id">{{$first_patient->gender}}</label>
                        <div class="check"></div>
                      </td>
                      <td>
                        <input type="radio" name="gender" value="{{$second_patient->gender}}">
                        <label id="label_id">{{$second_patient->gender}}</label>
                        <div class="check"></div>
                      </td>
                    </tr>
                    <tr>
                      <td>Group Id:</td>
                      <td>
                        <input type="radio" name="group_id" value="{{$first_patient->group_id}}" checked>
                        <label id="label_id">{{$first_patient->gender}}</label>
                        <div class="check"></div>
                      </td>
                      <td>
                        <input type="radio" name="group_id" value="{{$second_patient->group_id}}">
                        <label id="label_id">{{$second_patient->group_id}}</label>
                        <div class="check"></div>
                      </td>
                    </tr>
                    <tr>
                      <td>Group Id:</td>
                      <td>
                        <input type="radio" name="consent" value="{{$first_patient->consent}}" checked>
                        <label id="label_id">{{$first_patient->consent}}</label>
                        <div class="check"></div>
                      </td>
                      <td>
                        <input type="radio" name="consent" value="{{$second_patient->consent}}">
                        <label id="label_id">{{$second_patient->consent}}</label>
                        <div class="check"></div>
                      </td>
                    </tr>
                    <tr>
                      <td>Number of medical Cases:</td>
                      <td>
                        <label>{{$first_patient->medicalCases()->count()}}</label>
                      </td>
                      <td>
                        <label>{{$second_patient->medicalCases()->count()}}</label>
                      </td>
                    </tr>
                </tbody>
              </table>
              <input id="patient_id" type="text" name="firstp_id" value="{{$first_patient->id}}" hidden>
              <input id="patient_id" type="text" name="secondp_id" value="{{$second_patient->id}}" hidden>
              <input type="submit" name="button" value="Merge"/></form>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@stop
