<?php

namespace App\Http\Controllers;

use App\Device;
use App\Services\DeviceService;
use App\Http\Requests\DeviceRequest;
use App\Http\Resources\Device as DeviceResource;
use Illuminate\Support\Facades\DB;

class DeviceController extends Controller
{

    protected $deviceService;

    public function __construct(DeviceService $deviceService)
    {
        $this->deviceService = $deviceService;
        $this->middleware('can:Manage_Devices');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $devices = DeviceResource::collection(Device::all());
        return view('devices.index',['devices' => $devices->toJson(),]);
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
        $device = $this->deviceService->add($validated);
        return response()->json(new DeviceResource($device));
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
        $device = $this->deviceService->update($validated,$device);
        return response()->json(new DeviceResource($device));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Device  $device
     * @return \Illuminate\Http\Response
     */
    public function destroy(Device $device)
    {
        $id = $this->deviceService->remove($device);

        # Delete corresponding client
        DB::table('oauth_clients')->where('name', '=', $device->name)->delete();
        
        return response()->json([
            "message" => "Deleted",
            "id" => $id,
        ]);
    }
}
