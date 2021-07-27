<?php

namespace App\Http\Controllers;

use App\Device;
use Illuminate\Http\Request;
use App\Services\DeviceService;
use App\Http\Requests\DeviceRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Device as DeviceResource;

class DeviceController extends Controller
{

    protected $deviceService;

    public function __construct(DeviceService $deviceService)
    {
        $this->authorizeResource(Device::class);
        $this->deviceService = $deviceService;
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
        return response()->json([
            "message" => "Deleted",
            "id" => $id,
        ]);
    }
}
