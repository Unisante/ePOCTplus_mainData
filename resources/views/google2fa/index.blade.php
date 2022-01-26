@extends('layouts.app')

@section('content')
<div class="container">
    @if ($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first('message') }}
        </div>
    @endif
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-body" >
                <div class="card-header">
                  <span>Enter one time password</span>
                </div>
                <div class="card-body" >
                    <form class="form-horizontal" method="POST" action="{{ route('2fa') }}">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="one_time_password" class="col-md-4 control-label">One Time Password</label>
                                <div class="col-md-6">
                                    <input id="one_time_password" type="text" class="form-control" name="one_time_password" required autofocus>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4" >
                                    <button id="btn-login" type="submit" class="btn btn-primary" >
                                        Login
                                    </button>
                                    <br>
                                    <a href="abort-authentication">Abort authentication</a>
                                </div>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
