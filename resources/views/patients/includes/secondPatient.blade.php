<link href="{{ asset('css/custom.css') }}" rel="stylesheet">
@if($second_patient)
<div class="card card-color2">
  <div class="card-header">{{$second_patient->first_name}}'s Details</div>
  <div class="card-body">
    <div class="d-flex justify-content-between"> <span>Patient ID:</span> <span class="border-bottom" id="sp_id">{{$second_patient->local_patient_id}}</span></div>
    <div class="d-flex justify-content-between"> <span>First name:</span><span class="border-bottom" id="sp_first_name">{{$second_patient->first_name}}</span></div>
    <div class="d-flex justify-content-between"> <span>last name:</span><span class="border-bottom" id="sp_second_name">{{$second_patient->last_name}}</span></div>
    <div class="d-flex justify-content-between"> <span>Number of medical cases:</span> <span class="border-bottom"
        id="sp_cases_count">{{$second_patient->medicalCases()->count()}}</span></div>
  </div>
</div>
@else
<div class="card">
  <div class="card-header">No Second Patient</div>
</div>
@endif
