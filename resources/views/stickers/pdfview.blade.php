<style>
@page { margin: 0; }
</style>
@foreach ($uuids_qr_codes as $uuid_qr_code)
<div class="text-center" style="width:175.7;height:82.2pt;overflow:hidden;font-size:10px;">
        <div style="height:12.2pt;margin-left:10pt;">
            <div style="margin-top:18px;width:73.7;">
                {!! $uuid_qr_code[1] !!}
            </div>
            <div style="margin-left:80px;width:95.7;overflow-wrap:break-word;">
                <b>study_id:</b> {{$study_id}}
            </div>
            <div style="margin-left:80px;width:95.7;overflow-wrap:break-word;">
                <b>group_id:</b> {{$group_id}}
            </div>
            <div style="margin-left:80px;width:95.7;overflow-wrap:break-word;">
                <b>uid:</b> {{$uuid_qr_code[0]}}
            </div>
        </div>
</div>
@endforeach