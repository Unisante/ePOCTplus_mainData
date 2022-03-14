@extends('adminlte::page')

@section('content_header')
@stop

@section('content')
    <div class="col-md-9 col-lg-12 col-sm-12 pull-left" style="background: white;">
        <div class="panel-body">
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

            <h3 align="center">
                <b>Failed Json Folder Information</b>
            </h3>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>File name</th>
                        <th>Group ID</th>
                        <th>Date of consultation</th>
                        <th>Algorithm ID</th>
                        <th>Json Version</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($jsons as $json)
                        <tr>
                            <td>{{ $json->name }}</td>
                            <td>{{ $json->group_id }}</td>
                            <td>{{ $json->date }}</td>
                            <td>{{ $json->version_id }}</td>
                            <td>{{ $json->json_version }}</td>

                            {{-- <form action="{{ route('log-downloader', $log_file_name) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="pull-center btn btn-outline-info btn-sm" onclick="">
                                        <i class="fa fa-fw fa-download"></i> Download
                                    </button>
                                    <a class="pull-center btn btn-outline-info btn-sm" href="/logs/{{ $log_file_name }}"
                                        role="button">
                                        <i class="fa fa-fw fa-eye"></i> View
                                    </a>
                                </form> --}}
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">Each consultation has successfully been imported</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {!! $jsons->render() !!}
    </div>
@stop
