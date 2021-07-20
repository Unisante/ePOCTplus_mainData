<?php

namespace App\Http\Controllers;

use App\Device;
use Illuminate\Http\Request;
use App\Http\Requests\DeviceRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Device as DeviceResource;

class DeviceController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Device::class);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $devices = DeviceResource::collection(Auth::user()->devices);
        return view('devices.index',['devices' => $devices->toJson(),]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('devices.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DeviceRequest $request)
    {
        $validated = $request->validated();
        $device = new Device($validated);
        //Set Parameters for Passport Client Creation
        $userID = Auth::user()->id;
        $clientName = $device->name;
        //Redirect URL is the callback set for the medal Reader devices
        $redirectURL = getenv('HUB_CALLBACK_URL');
        //The following parameters make sure the client can only use the secure PKCE authorization flow
        $provider = null;
        $personalAccess = false;
        $password = false;
        $confidential = false;
        //Get Passport's client repository using the app parameters and create the client using the parameters
        $clientRepository = app('Laravel\Passport\ClientRepository');
        $client = $clientRepository->create(
            $userID,
            $clientName,
            $redirectURL,
            $provider,
            $personalAccess,
            $password,
            $confidential
        );
        //Update the device information and link it to the client ID
        $device->user_id = Auth::user()->id;
        $device->oauth_client_id = $client->id;
        $device->save();
        return response()->json([
            new DeviceResource($device)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Device  $device
     * @return \Illuminate\Http\Response
     */
    public function show(Device $device)
    {
        //$this->authorize('view',$device);
        return view('devices.details',[
            'device' => new DeviceResource($device),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Device  $device
     * @return \Illuminate\Http\Response
     */
    public function edit(Device $device)
    {
        //$this->authorize('edit',$device);
        return view('devices.edit',[
            'device' => new DeviceResource($device),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Device  $device
     * @return \Illuminate\Http\Response
     */
    public function update(DeviceRequest $request, Device $device)
    {
        $validated = $request->validated();
        $device->fill($validated)->save();
        $clientRepository = app('Laravel\Passport\ClientRepository');
        $client = $clientRepository->findForUser($device->oauth_client_id,Auth::user()->id);
        $clientRepository->update($client,$validated['name'],$client->redirect);
        return response()->json([
            new DeviceResource($device)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Device  $device
     * @return \Illuminate\Http\Response
     */
    public function destroy(Device $device)
    {
        $id = $device->id;
        $device->delete();
        $clientRepository = app('Laravel\Passport\ClientRepository');
        $client = $clientRepository->findForUser($device->oauth_client_id,Auth::user()->id);
        $clientRepository->delete($client);
        return response()->json([
            "message" => "Deleted",
            "id" => $id,
        ]);
    }


}
