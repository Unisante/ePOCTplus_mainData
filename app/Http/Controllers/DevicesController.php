<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DevicesController extends Controller
{
    //

    public function __construct(){
      $this->middleware('auth');
      $this->middleware('permission:manage-devices', ['only' => ['index']]);
    }

    public function index(Request $request){
    	return view('devices.index');
    }
}
