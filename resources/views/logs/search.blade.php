@if($log_level != '')
    <form action ={{$log_file_name}} method = "get">
@else
    <form action ={{$log_file_name}} method = "get">
@endif
<div class="input-group mb-3 float-right">
        <div class="input-group-prepend">
                <button type="submit" class="btn btn-danger">Search</button>
        </div>
        <input type="text" class="form-control" name = "search" value = "<?php echo $_GET['search'] ?? ''; ?>">
        @if($log_level != '')
        <input type="hidden" class="form-control" name = "log_level" value = "<?php echo $_GET['log_level'] ?? ''; ?>">
        @endif
    </div>
</form>