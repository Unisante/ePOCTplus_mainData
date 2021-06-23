<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\HealthFacilityRequest;
use App\HealthFacility;
use Illuminate\Support\Facades\Auth;



class HealthFacilityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $healthFacilities = $this->getHealthFacilities();
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
        $healthFacility = $this->newHealthFacility();
        $this->validateAndTranslate($request,$healthFacility);
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
    public function show($id)
    {
        //
        $healthFacility = $this->getHealthFacility($id);
        if ($healthFacility == null) {
            return redirect()->route('health-facilities.index');
        }
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
    public function edit($id)
    {
        $healthFacility = $this->getHealthFacility($id);
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
    public function update(HealthFacilityRequest $request, $id)
    {
        $healthFacility = $this->getHealthFacility($id);
        $this->validateAndTranslate($request,$healthFacility);
        $healthFacility->save();
        return $this->success();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        HealthFacility::destroy($id);
        return $this->success();
    }


    private function newHealthFacility(){
        $healthFacility = new HealthFacility();
        $healthFacility->user_id = Auth::user()->id;
        return $healthFacility;
    }

    private function getHealthFacility($id){
        $healthFacility = HealthFacility::where("id",$id)
        ->where("user_id",Auth::user()->id)
        ->first();
        return $healthFacility;
    }

    private function getHealthFacilities(){
        return HealthFacility::where("user_id",Auth::user()->id)->get();
    }


    private function validateAndTranslate(HealthFacilityRequest $request,HealthFacility $healthFacility){
        $validated = $request->validated();
        $healthFacility->name = $validated['name'];
        $healthFacility->country = $validated['country'];
        $healthFacility->area = $validated['area'];
        $healthFacility->pin_code = $validated['pin_code'];
        $healthFacility->facility_name = $validated['name'];
    }

    private function addDefaultValues(HealthFacility $healthFacility){
        $healthFacility->group_id = 1;
        $healthFacility->lat = 0;
        $healthFacility->long = 0;
        $healthFacility->local_data_ip = "127.0.0.1";
        $healthFacility->hf_mode = "standalone";
    }

    private function error(){
        return redirect()->route('health-facilities.index')->with("error","Whoops, something went wrong");
    }

    private function success(){
        return redirect()->route('health-facilities.index')->with('success',"successfully executed");
    }
}
