@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-body">
                <div class="card-header">
                  <span>Please set the email you use in Main Data</span>
                </div>
                <div class="card-body">
                    {{ Form::open(['route' => ['HomeController@forgotPassword']]) }}
                      <div class="form-group row">
                        {{Form::label('email', 'Email', array('class' => 'col-md-4 col-form-label text-md-right'))}}
                        <div class="col-md-6">
                          {{Form::email('email', null, array('autofocus'=>'autofocus','class'=>'form-control','required'=>'required'))}}
                          @if (count($errors) > 0)
                              <div class="alert alert-danger">
                                  <strong>Whoops!</strong>
                                  <span>There were some problems with the email,please contact the administrator</span>
                              </div>
                          @endif
                        </div>
                      </div>
                      <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                          {{Form::submit('Password Reset', array('class' => 'btn btn-outline-primary'))}}
                        </div>
                      </div>
                    {{ Form::close()}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
