@extends('adminlte::page')

<link href="{{ asset('css/followup.css') }}" rel="stylesheet">
<script src="{{ asset('js/followup.js') }}" defer></script>
<script src="{{ asset('js/chart.min.js') }}" defer></script>

@section('content')
<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header d-flex ">
          <span>
            <h3>Follow-ups In MedAl- Data</h3>
          </span>
        </div>
        <div class="card-body facility">
          @if (session('status'))
          <div class="alert alert-success" role="alert">
            {{ session('status') }}
          </div>
          @endif
          @include('layouts.datatable')
          <div class="row">
            <div class="col-md-8">
              <h2 id="facility_title"></h2>
              <canvas id="facility">
              </canvas>
            </div>
            <div class="col-md-4">
              <div class="scrollableDiv">

                @if(count($facilities)>0)
                <ul class="list-group" id="facilities">
                  @foreach($facilities as $facility)
                  <li class="list-group-item" id="{{$facility->group_id}}">{{$facility->name}}</li>
                  @endforeach
                </ul>
                @endif
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col">
              <div id="sent"><h3></h3></div>
              <div id="not_sent"><h3></h3></div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@stop
