    <form action="{{route('logs.index')}}" method="POST" style="display: inline;">
        @csrf
        <button type="submit" class="pull-center btn btn-info" onclick="">
            <i class="fa fa-fw fa-arrow-left"></i> Go back
        </button>
    </form>

    <form class="float-right" action="{{route('log-downloader', $log_file_name)}}" method="POST" style="display: inline;">
        @csrf
        <button type="submit" class="pull-center btn btn-success" onclick="">
            <i class="fa fa-fw fa-download"></i> <br> Download
        </button>
    </form>
