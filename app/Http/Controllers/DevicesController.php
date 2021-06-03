<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DevicesController extends Controller
{
    //

    public function __construct(){
      $this->middleware('auth');
      $this->middleware('permission:Manage_Devices', ['only' => ['index']]);
    }

    public function index(Request $request){
    	return view('devices.index');
    }
}
