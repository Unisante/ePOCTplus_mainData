
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ENV</th>
                @if ($log_level == '')
                <th>Level</th>
                @endif
                <th>Time</th>
                <th>Header</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
                <tr>
                    <td><label class="badge" style="background-color:#A020F0; color:white; font-size:90%;">{{$log->env}}</label></td>
                    @if ($log_level == '')
                    @if ($log->level == 'EMERGENCY')
                    <td><label class="badge" style="background-color:#B51B1D; color:white; font-size:90%;"><i class="fa fa-fw fa-bug"></i> {{$log->level}}</label></td>
                    @endif
                    @if ($log->level == 'ALERT')
                    <td><label class="badge" style="background-color:#D32E2C; color:white; font-size:90%;"><i class="fa fa-fw fa-bullhorn"></i> {{$log->level}}</label></td>
                    @endif
                    @if ($log->level == 'CRITICAL')
                    <td><label class="badge" style="background-color:#f44739; color:white; font-size:90%;"><i class="fa fa-fw fa-heartbeat"></i> {{$log->level}}</label></td>
                    @endif
                    @if ($log->level == 'ERROR')
                    <td><label class="badge" style="background-color:#FD5723; color:white; font-size:90%;"><i class="fa fa-fw fa-times-circle"></i> {{$log->level}}</label></td>
                    @endif
                    @if ($log->level == 'WARNING')
                    <td><label class="badge" style="background-color:#FF9003; color:white; font-size:90%;"><i class="fa fa-fw fa-exclamation-triangle"></i> {{$log->level}}</label></td>
                    @endif
                    @if ($log->level == 'NOTICE')
                    <td><label class="badge" style="background-color:#4EAD4F; color:white; font-size:90%;"><i class="fa fa-fw fa-exclamation-circle"></i> {{$log->level}}</label></td>
                    @endif
                    @if ($log->level == 'INFO')
                    <td><label class="badge" style="background-color:#1978D4; color:white; font-size:90%;"><i class="fa fa-fw fa-info-circle"></i> {{$log->level}}</label></td>
                    @endif
                    @if ($log->level == 'DEBUG')
                    <td><label class="badge" style="background-color:#8DCBFC; color:white; font-size:90%;"><i class="fa fa-fw fa-life-ring"></i> {{$log->level}}</label></td>
                    @endif
                    @endif
                    <td><label class="badge" style="background-color:gray; color:white; font-size:90%;">{{$log->time}}</label></td>
                    <td style ="word-break:break-all;"><div class="header" style="overflow:hidden;height:30px;cursor:zoom-in;">{!! nl2br(e($log->header)) !!}</div></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script type="application/javascript">
        $(function() {
  $('.header').click(function() {
      if(this.style.overflow == 'hidden'){
        $(this).css({
            'overflow': 'auto',
            'height': 'auto',
            'cursor':'zoom-out'
        });

      }else{
        $(this).css({
            'overflow': 'hidden',
            'height': '30px',
            'cursor':'zoom-in'
        });
      }
  });
});
    </script>