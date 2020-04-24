@if($second_medical_case)
<div class="card">
  <div class="card-color">
    <div class="card-header">
      <span class="font-weight-bold">Created At: </span>
      <span class="border-bottom">{{$second_medical_case->created_at}}</span>
    </div>
    <div class="card-header">
      <span class="font-weight-bold">Updated At: </span>
      <span class="border-bottom">{{$second_medical_case->created_at}}</span> </div>
    <div class="card-header">
      <div>
        <span class="font-weight-bold">Patient Name:</span>
        <span class="border-bottom">
          {{$second_medical_case->patient->first_name}}
          {{$second_medical_case->patient->last_name}}
        </span>
      </div>
    </div>
  </div>
</div>
@else
<div class="card-header">No first Medical Case</div>
@endif

