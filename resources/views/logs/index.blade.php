@extends('adminlte::page')

@section('content_header')
@stop

@section('content')
<div class="col-md-9 col-lg-12 col-sm-12 pull-left" style="background: white;">
    <div class="panel-body">
        <form action="/users" method="get">
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p>{{ $message }}</p>
                </div>
            @endif
            @if ($message = Session::get('error'))
                <div class="alert alert-danger">
                    <p>{{ $message }}</p>
                </div>
            @endif

            <H3 align="center">
                <b>Logs Information</b>
            </H3>
            {{ csrf_field() }}
        </form>

        <table class="table table-bordered table-striped" >
            <thead>
                <tr>
                    <th>File name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log_file_name)
                    <tr>
                        <td>{{$log_file_name}}</td>
                        <td>
                            <form action="{{route('log-downloader', $log_file_name)}}" method="POST">
                                @csrf
                                <button type="submit" class="pull-center btn btn-outline-info btn-sm" onclick="">
                                    <i class="fa fa-fw fa-download"></i> Download
                                </button>
                                <a class="pull-center btn btn-outline-info btn-sm" href="/logs/{{$log_file_name}}" role="button">
                                    <i class="fa fa-fw fa-eye"></i> View
                                </a>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop
