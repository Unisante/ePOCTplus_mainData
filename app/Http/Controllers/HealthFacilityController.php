<?php

namespace App\Http\Controllers;

use App\Device;
use App\HealthFacility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\HealthFacilityRequest;
use App\Http\Resources\Device as DeviceResource;



class HealthFacilityController extends Controller
{


    public function __construct()
    {
        $this->authorizeResource(HealthFacility::class);
    }
    /**
     * Return an index of the resources owned by the user
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $healthFacilities =  Auth::user()->healthFacilities;
        return view("healthFacilities.index",[
            "healthFacilities" => $healthFacilities
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HealthFacilityRequest $request){
        $validated = $request->validated();
        $healthFacility = new HealthFacility($validated);
        $healthFacility->user_id = Auth::user()->id;
        $this->addDefaultValues($healthFacility);
        $healthFacility->save();
        return response()->json([
            $healthFacility,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request\HealthFacilityRequest  $request
     * @param  \App\HealthFacility $healthFacility
     * @return \Illuminate\Http\Response
     */
    public function update(HealthFacilityRequest $request, HealthFacility $healthFacility)
    {
        $validated = $request->validated();
        $healthFacility->fill($validated)->save();
        return response()->json([
            $healthFacility,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\HealthFacility $healthFacility
     * @return \Illuminate\Http\Response
     */
    public function destroy(HealthFacility $healthFacility)
    {
        $id = $healthFacility->id;
        $healthFacility->delete();
        return response()->json([
            "message" => "Deleted",
            "id" => $id,
        ]);
    }

    public function manageDevices(HealthFacility $healthFacility){
        error_log(Auth::user()->id);
        error_log($healthFacility->user_id);
        $this->authorize('manageDevices',$healthFacility);
        $devices = DeviceResource::collection($healthFacility->devices);
        $unassignedDevices = DeviceResource::collection(Auth::user()->unassignedDevices());
        return response()->json([
            "devices" => $devices->values(),
            "unassignedDevices" => $unassignedDevices->values(),
            "healthFacility" => $healthFacility,
        ]);
    }

    public function assignDevice(HealthFacility $healthFacility,Device $device){
        $this->authorize('assignDevice',[$healthFacility,$device]);
        $device->health_facility_id = $healthFacility->id;
        $device->save();
        return response()->json([
            new DeviceResource($device)
        ]);
    }

    public function unassignDevice(HealthFacility $healthFacility,Device $device){
        $this->authorize("unassignDevice",[$healthFacility,$device]);
        $device->health_facility_id = null;
        $device->save();
        return response()->json([
            new DeviceResource($device)
        ]);
    }


    private function addDefaultValues(HealthFacility $healthFacility){
        $healthFacility->group_id = 1;
        $healthFacility->facility_name = "not used anymore";
    }
}
