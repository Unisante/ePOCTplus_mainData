@extends('adminlte::page')
@section('content')
<link href="{{ asset('css/datatable.css') }}" rel="stylesheet">
<link href="{{ asset('css/background.css') }}" rel="stylesheet">
<div class="container-fluid">

        <div class="col-md-10">
              @if ($errors->any())
                  <div class="alert alert-danger">
                      <ul>
                          @foreach ($errors->all() as $error)
                              <li>{{ $error }}</li>
                          @endforeach
                      </ul>
                  </div>
              @endif
            <div class="card">
                <div class="card-header">Dashboard</div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="row">
                      <div class="col border-right">
                        <div>
                          <span>User: </span>
                          <span>{{$currentUser->name}}</span>
                        </div>
                        <div>
                          @Foreach($currentUser->roles as $role)
                          <span>Role: </span>
                          <span>{{$role->name}}</span>
                          @endforeach
                        </div>
                      </div>
                      @Foreach($currentUser->roles as $role)
                      @if($role->name=='Administrator')
                      <div class="col border-right">
                        <div>
                          <span>Main Data Users: </span>
                          <span>{{$userCount}}</span>
                        </div>
                      </div>
                      @endif
                      @endforeach
                      <div class="col ">
                        <div>
                         <span>Medical Cases: </span>
                         <span>{{$mdCases}}</span>
                        </div>
                        <div>
                          <span>Patients: </span>
                          <span>{{$patientCount}}</span>
                        </div>
                      </div>
                    </div>
                </div>
            </div>
            <div class="card">
              <div class="card-header">Download By Date</div>
              <div class="card-body">
                  @if (session('status'))
                      <div class="alert alert-success" role="alert">
                          {{ session('status') }}
                      </div>
                  @endif
                  <form method="POST" action="{{ route('exports.exportZipByDate') }}" accept-charset="UTF-8">
                    {{ csrf_field() }}
                    <div class="row">
                      <div class="col border-right">
                            <label for="fromDate">From:</label>
                            <input type="date" id="fromDate" name="fromDate" value="{{$oldest_date}}">
                      </div>
                      <div class="col border-right">
                          <label for="toDate">To:</label>
                          <input type="date" id="toDate" name="toDate" value="{{$newest_date}}">
                      </div>
                      <div class="col border-right">
                        <div>
                          <input type="submit" name="Download" value="Extract">
                        </div>
                      </div>
                    </div>
                  </form>
              </div>
          </div>
        </div>
    </div>
    </div>
</div>
@stop
