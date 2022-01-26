@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-body">
                <div class="card-header">
                  <span>Please set the new password</span>
                </div>
                <div class="card-body">
                    {{ Form::open(['route' => ['home.makePassword']]) }}
                      <div class="form-group row">
                        {{Form::label('password', 'Password', array('class' => 'col-md-4 col-form-label text-md-right'))}}
                        <div class="col-md-6">
                          {{Form::password('password', null, array('autofocus'=>'autofocus','class'=>'form-control','required'=>'required'))}}
                          @if (session('success'))
                              <div class="alert alert-danger">
                              <span>{{ session('success') }}</span>
                              </div>
                          @endif
                          @if (session('error'))
                              <div class="alert alert-danger">
                                <span>{{session('error')}}</span>
                              </div>
                          @endif
                        </div>
                      </div>
                      <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                          {{Form::submit('Reset', array('class' => 'btn btn-outline-dark'))}}
                        </div>
                      </div>
                    {{ Form::close()}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
