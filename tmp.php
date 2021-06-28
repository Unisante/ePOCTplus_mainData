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
        $this->authorizeResource(HealthFacility::class, 'healthFacility');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $healthFacilities = Auth::user()->healthFacilities;
        return view("healthFacilities.index",[
            "facilities" => $healthFacilities,
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
        return $this->success();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(HealthFacility $healthFacility)
    {
        return view("healthFacilities.details",[
            "facility" => $healthFacility,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(HealthFacilityRequest $request, HealthFacility $healthFacility)
    {
        $validated = $request->validated();
        $healthFacility->fill($validated)->save();
        return $this->success();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(HealthFacility $healthFacilty)
    {
        $healthFacility->delete();
        return $this->success();
    }

    private function addDefaultValues(HealthFacility $healthFacility){
        $healthFacility->group_id = 1;
        $healthFacility->facility_name = "not used anymore";
    }

    private function success(){
        return redirect()->route('health-facilities.index')->with('success',"successfully executed");
    }
}
