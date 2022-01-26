@extends('adminlte::page')



@section('content_header')
@stop

@section('content')

@include('partials.errors')
@include('partials.success')


<div class="col-md-9 col-lg-12 col-sm-12 pull-left" style="background: white;">
    <h3 align="center"><b>Showing Log [{{$log_file_name}}]</b></h3>

    @include('logs.buttons')

    <br><br><br>
    @include('logs.labels')
    <br>

    @if(count($logs) > 0 || $search != '')
    <div class="float-left">
        <br>
        @include('logs.search')
    </div>
    <div class="float-right">
        <br>
            @if($search != '' && $log_level != '')
                {{ $logs->appends(['search' => $search, 'log_level' => $log_level])->links() }}
            @elseif($search == '' && $log_level != '')
                {{ $logs->appends(['log_level' => $log_level])->links() }}
            @elseif($search != '' && $log_level == '')
                {{ $logs->appends(['search' => $search])->links() }}
            @else
                {{ $logs->links() }}
            @endif
    </div>

    </div id = "table_data">
        @include('logs.pagination')
    </div>
    @endif
</div>

<script type="text/javascript">
    $(document).ready(function() {
    });
</script>
@stop
