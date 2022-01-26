<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuditsController extends Controller
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
        $audits = DB::table('audits')->get();
        return view('audits.index', compact('audits'));
    }

    /**
    * Show individual audit
    * @params $id
    * @return $audit
    */
    public function show($id){
        $audit = DB::table('audits')->find($id);
        Log::info("User with id " . Auth::user()->id . " checked out audit " . $id . ".");
        return view('audits.show', compact('audit'));
    }
}