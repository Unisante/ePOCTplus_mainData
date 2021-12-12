@if($first_medical_case)
<div class="card">
  <div class="card-color">
    <div class="card-header">
      <div class="d-flex justify-content-between">
        <span class="font-weight-bold text-white">Created At: </span>
        <span class="border-bottom text-white">{{$first_medical_case->created_at}}</span>
      </div>
    </div>
    <div class="card-header ">
      <div class="d-flex justify-content-between">
        <span class="font-weight-bold text-white">Updated At: </span>
        <span class="border-bottom text-white">{{$first_medical_case->created_at}}</span>
      </div>
    </div>
    <div class="card-header">
      <div class="d-flex justify-content-between">
        <span class="font-weight-bold text-white">Patient Name:</span>
        <span class="border-bottom text-white">
          {{$first_medical_case->patient->first_name}}
          {{$first_medical_case->patient->last_name}}
        </span>
      </div>
    </div>
    <div class="card-header ">
        <div class="d-flex justify-content-between">
          <span class="font-weight-bold text-white">Medical Case Id: </span>
          <span class="border-bottom text-white">{{$first_medical_case->local_medical_case_id}}</span>
        </div>
      </div>
  </div>
</div>
@else
<div class="card-header">No first Medical Case</div>
@endif
