<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\HealthFacilityRequest;
use App\HealthFacility;
use Illuminate\Support\Facades\Auth;



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
            "healthFacilities" => $healthFacilities,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("healthFacilities.create");
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
        return $healthFacility;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\HealthFacility $healthFacility
     * @return \Illuminate\Http\Response
     */
    public function show(HealthFacility $healthFacility)
    {
        $devices = $healthFacility->devices;
        $unassignedDevices = Auth::user()->unassignedDevices();
        return view("healthFacilities.details",[
            "facility" => $healthFacility,
            "devices" => $devices,
            "unassignedDevices" => $unassignedDevices,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\HealthFacility $healthFacility
     * @return \Illuminate\Http\Response
     */
    public function edit(HealthFacility $healthFacility)
    {
        return view('healthFacilities.edit',[
            "facility" => $healthFacility,
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
        return $healthFacility;
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
        return response([
            "message" => "Deleted",
            "id" =>  $id,
        ]);
    }


    public function devices(HealthFacility $healthFacility){
        return $healthFacility->devices;
    }

    public function assignDevice(HealthFacility $healthFacility,Device $device){
        $device->health_facility_id = $healthFacility->id;
        return $device;
    }

    public function unassignDevice(HealthFacility $healthFacility,Device $device){
        $device->health_facility_id = null;
        return response([
            "message" => "Unassigned",
        ]);
    }


    private function addDefaultValues(HealthFacility $healthFacility){
        $healthFacility->group_id = 1;
        $healthFacility->facility_name = "not used anymore";
    }
}
