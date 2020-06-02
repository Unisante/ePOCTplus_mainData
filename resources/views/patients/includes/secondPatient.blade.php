<link href="{{ asset('css/custom.css') }}" rel="stylesheet">
@if($second_patient)
<div class="card card-color2">
  <div class="card-header">{{$second_patient->first_name}}'s Details</div>
  <div class="card-body">
    <div>First name: <span class="border-bottom" id="sp_first_name">{{$second_patient->first_name}}</span><br /></div>
    <div>last name: <span class="border-bottom" id="sp_second_name">{{$second_patient->last_name}}</span><br /></div>
    <div>Number of medical cases: <span class="border-bottom"
        id="sp_cases_count">{{$second_patient->medicalCases()->count()}}</span><br /></div>
  </div>
</div>
@else
<div class="card">
  <div class="card-header">No Second Patient</div>
</div>
@endif
