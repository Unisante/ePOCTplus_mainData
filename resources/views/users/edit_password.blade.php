@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
@stop

@section('content')

      @include('partials.errors')
      @include('partials.success')

<div class="col-md-9 col-lg-9 col-sm-9 pull-left" style="background: white;">

<!-- Example row of columns -->
<h3 align="center">Change Password </h3>
      <div class="row col-sm-12 col-md-12 col-lg-12" style="background:white; margin: 10px">
      <form method="GET" action="/update_password">
     
                           {{csrf_field()}}

                           <div class="form-group row">
                            <label for="old_password" class="col-md-2 control-label">Old Password</label>
                            <div class="col-md-6">
                                <input placeholder="Enter old_password" id="old_password" type="password" class="form-control" name="old_password" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-2 control-label">New Password</label>
                            <div class="col-md-6">
                                <input placeholder="Enter New Password" id="password" type="password" class="form-control" name="password" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-2 control-label">Confirm Password</label>
                            <div class="col-md-6">
                                <input placeholder="Confirm your Password" id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-success">
                                    Save
                                </button>
</form>  
      </div> 

     </div> 
    


        @stop