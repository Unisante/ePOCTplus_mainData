<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * This Controller returns the devices.index blade template used to manage OAuth clients / hub or reader devices
 */
class DevicesController extends Controller
{
    //

    public function __construct(){
      $this->middleware('auth');
      //You need to permission to manage devices to access this controller
      $this->middleware('permission:Manage_Devices', ['only' => ['index']]);
    }

    public function index(Request $request){
    	return view('devices.index');
    }
}
