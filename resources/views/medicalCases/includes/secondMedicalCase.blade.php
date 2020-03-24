@if($second_medical_case)
<div class="card">
  <div class="sticky-top" style="background-color:grey;">
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
  <div class="card-body" style="height:450px;overflow:auto;">
    @foreach($second_medical_case_info as $case)
    <div class="card-md">
      <div class="card-header">
        <span class="font-weight-bold">Label: </span>
        <span>{{$case->question->label}}</span>
      </div>
      <div class="card-header">
        <span class="font-weight-bold">Stage: </span>
        <span>{{$case->question->stage}}</span>
      </div>
      <div class="card-header">
        <span class="font-weight-bold">Description: </span>
        <span>{{$case->question->description}}</span>
      </div>
      <div class="card-header">
        <span class="font-weight-bold">Answer Type: </span>
        <span>{{$case->answerType}}</span>
      </div>
      <div class="card-header">
        <span class="font-weight-bold">Answer: </span>
        <span>{{$case->answer->label}}</span>
      </div>
    </div>
    <hr style="background-color:grey;border:7px solid grey;border-radius:3px;">
    @endforeach
  </div>
</div>

@else
<div class="card-header">No first Patient</div>
@endif
