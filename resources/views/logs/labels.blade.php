<table style="width:100%;text-align:center;">
        <tbody>
            <tr>
                <td>
                    <a href="{{$log_file_name}}">
                        @if ($log_level == '')
                        <label class="badge" style="background-color:LightGray; color:white; font-size:90%;cursor:pointer;"><i class="fa fa-fw fa-list"></i> ALL</label>
                        <label class="badge" style="background-color:LightGray; color:white; font-size:90%;cursor:pointer;">{{$log_levels_num['ALL'] ?? 0}}</label>
                        @else
                        <label class="badge" style="background-color:gray; color:white; font-size:90%;cursor:pointer;"><i class="fa fa-fw fa-list"></i> ALL</label>
                        <label class="badge" style="background-color:gray; color:white; font-size:90%;cursor:pointer;">{{$log_levels_num['ALL'] ?? 0}}</label>
                        @endif
                    </a>
                </td>
                <td>
                    <a href="{{$log_file_name}}?log_level=EMERGENCY">
                        @if ($log_level == 'EMERGENCY' || $log_level == '')
                        <label class="badge" style="background-color:#B51B1D; color:white; font-size:90%;cursor:pointer;"><i class="fa fa-fw fa-bug"></i> EMERGENCY</label>
                        <label class="badge" style="background-color:#B51B1D; color:white; font-size:90%;cursor:pointer;">{{$log_levels_num['EMERGENCY'] ?? 0}}</label>
                        @else
                        <label class="badge" style="background-color:gray; color:white; font-size:90%;cursor:pointer;"><i class="fa fa-fw fa-bug"></i> EMERGENCY</label>
                        <label class="badge" style="background-color:gray; color:white; font-size:90%;cursor:pointer;">{{$log_levels_num['EMERGENCY'] ?? 0}}</label>
                        @endif
                    </a>
                </td>
                <td>
                    <a href="{{$log_file_name}}?log_level=ALERT">
                        @if ($log_level == 'ALERT' || $log_level == '')
                        <label class="badge" style="background-color:#D32E2C; color:white; font-size:90%;cursor:pointer;"><i class="fa fa-fw fa-bullhorn"></i> ALERT</label>
                        <label class="badge" style="background-color:#D32E2C; color:white; font-size:90%;cursor:pointer;">{{$log_levels_num['ALERT'] ?? 0}}</label>
                        @else
                        <label class="badge" style="background-color:gray; color:white; font-size:90%;cursor:pointer;"><i class="fa fa-fw fa-bullhorn"></i> ALERT</label>
                        <label class="badge" style="background-color:gray; color:white; font-size:90%;cursor:pointer;">{{$log_levels_num['ALERT'] ?? 0}}</label>
                        @endif
                    </a>
                </td>
                <td>
                    <a href="{{$log_file_name}}?log_level=CRITICAL">
                        @if ($log_level == 'CRITICAL' || $log_level == '')
                        <label class="badge" style="background-color:#f44739; color:white; font-size:90%;cursor:pointer;"><i class="fa fa-fw fa-heartbeat"></i> CRITICAL</label>
                        <label class="badge" style="background-color:#f44739; color:white; font-size:90%;cursor:pointer;">{{$log_levels_num['CRITICAL'] ?? 0}}</label>
                        @else
                        <label class="badge" style="background-color:gray; color:white; font-size:90%;cursor:pointer;"><i class="fa fa-fw fa-heartbeat"></i> CRITICAL</label>
                        <label class="badge" style="background-color:gray; color:white; font-size:90%;cursor:pointer;">{{$log_levels_num['CRITICAL'] ?? 0}}</label>
                        @endif
                    </a>
                </td>
                <td>
                    <a href="{{$log_file_name}}?log_level=ERROR">
                        @if ($log_level == 'ERROR' || $log_level == '')
                        <label class="badge" style="background-color:#FD5723; color:white; font-size:90%;cursor:pointer;"><i class="fa fa-fw fa-times-circle"></i> ERROR</label>
                        <label class="badge" style="background-color:#FD5723; color:white; font-size:90%;cursor:pointer;">{{$log_levels_num['ERROR'] ?? 0}}</label>
                        @else
                        <label class="badge" style="background-color:gray; color:white; font-size:90%;cursor:pointer;"><i class="fa fa-fw fa-times-circle"></i> ERROR</label>
                        <label class="badge" style="background-color:gray; color:white; font-size:90%;cursor:pointer;">{{$log_levels_num['ERROR'] ?? 0}}</label>
                        @endif
                    </a>
                </td>
                <td>
                    <a href="{{$log_file_name}}?log_level=WARNING">
                        @if ($log_level == 'WARNING' || $log_level == '')
                        <label class="badge" style="background-color:#FF9003; color:white; font-size:90%;cursor:pointer;"><i class="fa fa-fw fa-exclamation-triangle"></i> WARNING</label>
                        <label class="badge" style="background-color:#FF9003; color:white; font-size:90%;cursor:pointer;">{{$log_levels_num['WARNING'] ?? 0}}</label>
                        @else
                        <label class="badge" style="background-color:gray; color:white; font-size:90%;cursor:pointer;"><i class="fa fa-fw fa-exclamation-triangle"></i> WARNING</label>
                        <label class="badge" style="background-color:gray; color:white; font-size:90%;cursor:pointer;">{{$log_levels_num['WARNING'] ?? 0}}</label>
                        @endif
                    </a>
                </td>
                <td>
                    <a href="{{$log_file_name}}?log_level=NOTICE">
                        @if ($log_level == 'NOTICE' || $log_level == '')
                        <label class="badge" style="background-color:#4EAD4F; color:white; font-size:90%;cursor:pointer;"><i class="fa fa-fw fa-exclamation-circle"></i> NOTICE</label>
                        <label class="badge" style="background-color:#4EAD4F; color:white; font-size:90%;cursor:pointer;">{{$log_levels_num['NOTICE'] ?? 0}}</label>
                        @else
                        <label class="badge" style="background-color:gray; color:white; font-size:90%;cursor:pointer;"><i class="fa fa-fw fa-exclamation-circle"></i> NOTICE</label>
                        <label class="badge" style="background-color:gray; color:white; font-size:90%;cursor:pointer;">{{$log_levels_num['NOTICE'] ?? 0}}</label>
                        @endif
                    </a>
                </td>
                <td>
                    <a href="{{$log_file_name}}?log_level=INFO">
                        @if ($log_level == 'INFO' || $log_level == '')
                        <label class="badge" style="background-color:#1978D4; color:white; font-size:90%;cursor:pointer;"><i class="fa fa-fw fa-info-circle"></i> INFO</label>
                        <label class="badge" style="background-color:#1978D4; color:white; font-size:90%;cursor:pointer;">{{$log_levels_num['INFO'] ?? 0}}</label>
                        @else
                        <label class="badge" style="background-color:gray; color:white; font-size:90%;cursor:pointer;"><i class="fa fa-fw fa-info-circle"></i> INFO</label>
                        <label class="badge" style="background-color:gray; color:white; font-size:90%;cursor:pointer;">{{$log_levels_num['INFO'] ?? 0}}</label>
                        @endif
                    </a>
                </td>
                <td>
                    <a href="{{$log_file_name}}?log_level=DEBUG">
                        @if ($log_level == 'DEBUG' || $log_level == '')
                        <label class="badge" style="background-color:#8DCBFC; color:white; font-size:90%;cursor:pointer;"><i class="fa fa-fw fa-life-ring"></i> DEBUG</label>
                        <label class="badge" style="background-color:#8DCBFC; color:white; font-size:90%;cursor:pointer;">{{$log_levels_num['DEBUG'] ?? 0}}</label>
                        @else
                        <label class="badge" style="background-color:gray; color:white; font-size:90%;cursor:pointer;"><i class="fa fa-fw fa-life-ring"></i> DEBUG</label>
                        <label class="badge" style="background-color:gray; color:white; font-size:90%;cursor:pointer;">{{$log_levels_num['DEBUG'] ?? 0}}</label>
                        @endif
                    </a>
                </td>
            </tr>
        </tbody>
    </table>