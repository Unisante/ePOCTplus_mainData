@if($first_patient)
<div class="card">
  <div class="card-header">{{$first_patient->first_name}}'s Details</div>
  <div class="card-body">
    <div>First name: <span class="border-bottom" id="fp_first_name">{{$first_patient->first_name}}</span><br /></div>
    <div>last name: <span class="border-bottom" id="fp_second_name">{{$first_patient->last_name}}</span><br /></div>
    <div>Number of medical cases: <span class="border-bottom"
        id="fp_cases_count">{{$first_patient->medicalCases()->count()}}</span><br /></div>
  </div>
</div>

@else
<div class="card-header">No first Patient</div>
@endif
