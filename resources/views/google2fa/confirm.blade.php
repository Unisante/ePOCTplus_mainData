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
                    <form class="form-horizontal" method="POST" action="{{ route('re-authenticate-confirmed') }}">
                    {{ csrf_field() }}
                        <p>A new secret will be generated. This action will invalidate the previous secret key.</p>
                        <div style="text-align: center;">
                            <button type="submit" class="btn btn-primary" >
                                Continue
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection