@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-body">
                <div class="card-header">
                  <span>Set up Google Authenticator</span>
                </div>
                <div class="card-body">
                    <p>Set up your two factor authentication by scanning the barcode below. Alternatively, you can use the code <b>{{ $secret }}</b></p>
                    <div class="justify-content-center" style="text-align: center;">
                        {!! $QR_Image !!}
                    </div>
                    @if (@$reauthenticating)
                    <div style="text-align: center;">
                        <a href="/home"><button class="btn-primary">Done</button></a>
                    </div>
                    @else
                    <p>You must set up your Google Authenticator app before continuing. You will be unable to login otherwise.</p>
                    <div style="text-align: center;">
                        <a href="/complete-registration"><button class="btn-primary">Complete Registration</button></a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection