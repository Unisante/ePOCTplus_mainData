<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

class LogsController extends Controller
{
    /**
    * To block any non-authorized user
    * @return void
    */
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('permission:See_Logs');
    }

    /**
    * Display a listing of the resource.
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request){
        if(!Auth::check()){
            return;
        }
        $logs = [];
        foreach(File::files(storage_path('logs')) as $path){
            $exploded_path = explode('/', $path);
            $file_name = end($exploded_path);
            $file_name_exploded = explode('.', $file_name);

            $log = [];
            if(count($file_name_exploded) == 2 && ($file_name_exploded[1] ?? '') === 'log'){
                $logs[] = $file_name_exploded[0];
            }
        }
        return view('logs.index', compact('logs'));
    }

    /**
     * Returns the log date.
     */
    protected function getLogDate($line){
        $start  = strpos($line, '[');
        if($start != 0){
            return false;
        }
        $end = strpos($line, ']', $start + 1);
        $length = $end - $start;
        $date = substr($line, $start + 1, $length - 1);
        return $date;
    }

    /**
     * Returns the log level.
     */
    protected function getLogLevel($line){
        $start  = strpos($line, '.');
        $end  = strpos($line, ': ');
        $length = $end - $start;
        return substr($line, $start + 1, $length - 1);
    }

    /**
     * Returns the log time.
     */
    protected function getLogTime($line){
        $date = $this->getLogDate($line);
        return date("H:i:s", strtotime($date));
    }

    /**
     * Returns the log env.
     */
    protected function getLogEnv($line){
        $start  = strpos($line, '] ');
        $end  = strpos($line, '.');
        $length = $end - $start;
        return substr($line, $start + 1, $length - 1);
    }

    protected function getLogHeader($line){
        $start  = strpos($line, ': ');
        $end = strlen($line) - 1;
        $length = $end - $start;
        return substr($line, $start + 1, $length);
    }

    /**
     * Checks if a date is valid.
     */
    function isvalidDate($date, $format = 'Y-m-d H:i:s'){
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    /**
     * Checks if a line is a new log.
     */
    protected function isNewLog($line){
        $start  = strpos($line, '[');
        if($start != 0){
            return false;
        }
        $end = strpos($line, ']', $start + 1);
        $length = $end - $start;
        $date = substr($line, $start + 1, $length - 1);
        return $this->isvalidDate($date);
    }

    /**
     * Get an array of logs given its type.
     */
    protected function getLogsPerType($log_path, $log_level, $search, &$log_levels_num){
        // Load all logs
        $logs = [];
        $current_log = (object) ['is_log' => false];
        $search = trim(strtolower($search));


        foreach(file($log_path) as $line){
            if($this->isNewLog($line)){
                // Old valid log must be added when we discover a new log.
                if($current_log->is_log){
                    if(($search == '') || ($search != '' && str_contains(strtolower($current_log->header), $search))){
                        $logs[] = clone $current_log;
                    }
                }
                // Check if the next log should be added given its type.
                $line_log_level = $this->getLogLevel($line);
                // Increment log level number.
                $curr_log_levels_num = $log_levels_num[$line_log_level] ?? 0;
                $curr_log_levels_num_all = $log_levels_num['ALL'] ?? 0;
                $log_levels_num[$line_log_level] = $curr_log_levels_num + 1;
                $log_levels_num['ALL'] = $curr_log_levels_num_all + 1;

                $current_log->is_log = ($log_level == '') || ($line_log_level == $log_level);
                $current_log->env = $this->getLogEnv($line);
                $current_log->header = $this->getLogHeader($line);
                $current_log->level = $this->getLogLevel($line);
                $current_log->time = $this->getLogTime($line);
            }else{
                // Append line to current log, because this line belongs to it.
                if($current_log->is_log){
                    $current_log->header = $current_log->header . $line;
                }
            }
        }

        return $logs;
    }

    /**
    * Display the specified resource.
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show($log_file_name){
        $log_path = storage_path('logs') . '/' . $log_file_name . '.log';
        $log_level = $_GET['log_level'] ?? '';
        $search = $_GET['search'] ?? '';
        $log_levels_num = [];
        $cache_name = $log_file_name . "-" . $log_level . "-" . $search;

        // check if in cache, otherwise compute the list of logs.
        if(Cache::has('logs' . $cache_name) 
            && Cache::has('log_levels_num' . $cache_name) 
            && ($_GET['page'] ?? null) != null){
            $logs_array = Cache::get('logs' . $cache_name);
            $log_levels_num = Cache::get('log_levels_num' . $cache_name);
        }else{
            // forget variables, and process it again.
            Cache::forget('logs' . $cache_name);
            Cache::forget('log_levels_num' . $cache_name);

            $logs_array = $this->getLogsPerType($log_path, $log_level, $search, $log_levels_num);
            Cache::rememberForever('logs' . $cache_name, function () use ($logs_array) {
                return $logs_array;
            });
            Cache::rememberForever('log_levels_num' . $cache_name, function () use ($log_levels_num) {
                return $log_levels_num;
            });  
        }
        $logs = $this->paginate($logs_array);
        $logs->setPath($log_file_name);
        $num_pages = ceil($logs->total() / $logs->perPage());

        return view('logs.informations', compact('log_level', 'num_pages', 'log_file_name', 'logs', 'log_levels_num', 'search'));
    }

    /**
     * Paginate an array.
     */
    public function paginate($items, $perPage = 20, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    /**
     * Download log file given its name.
     */
    public function downloadLog($log_file_name){
        $file = storage_path('logs') . '/' . $log_file_name . '.log';
        $headers = array('Content-Type: text/plain',);

        try{
            $download = Response::download($file, $log_file_name . '.log', $headers);
            Log::info("User with id " . Auth::user()->id . " downloaded the log file " . $log_file_name . ".log.");
            return $download;
        }catch(FileNotFoundException $e){
            Log::info("User with id " . Auth::user()->id . " tried to download the log file " . $log_file_name . ".log, but it does not exist.");
            return redirect()->route('logs.index')->with('error', "Could not download log file " . $file . " : the file does not exist.");
        }
    }

    /**
     * Download log file given its name.
     */
    public function downloadTypeLog($log_file_name){
        $file = storage_path('logs') . '/' . $log_file_name . '.log';
        $headers = array('Content-Type: text/plain',);

        try{
            $download = Response::download($file, $log_file_name . '-' . $_POST['log_type'] . '.log', $headers);
            Log::info("User with id " . Auth::user()->id . " downloaded the log file " . $log_file_name . ".log.");
            return $download;
        }catch(FileNotFoundException $e){
            Log::info("User with id " . Auth::user()->id . " tried to download the log file " . $log_file_name . ".log, but it does not exist.");
            return redirect()->route('logs.index')->with('error', "Could not download log file " . $file . " : the file does not exist.");
        }
    }
}