<?php

namespace App\Http\Controllers;

use App\Device;
use App\HealthFacility;
use Illuminate\Http\Request;
use App\Services\AlgorithmService;
use Illuminate\Support\Facades\Auth;
use App\Services\HealthFacilityService;
use App\Http\Requests\HealthFacilityRequest;
use App\Http\Resources\Device as DeviceResource;



class HealthFacilityController extends Controller
{
    protected $healthFacilityService;
    protected $algorithmService;

    public function __construct(HealthFacilityService $healthFacilityService, 
                                AlgorithmService $algorithmService)
    {
        $this->authorizeResource(HealthFacility::class);
        $this->healthFacilityService = $healthFacilityService;
        $this->algorithmService = $algorithmService;
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
        return response()->json($healthFacility);
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
        return response()->json($healthFacility);
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

    /**
     * Returns the resolved health facility as well as all the device assigned to it and the devices that are not assigned to any HF
     */
    public function manageDevices(HealthFacility $healthFacility){
        $this->authorize('manageDevices',$healthFacility);
        $devices = DeviceResource::collection($healthFacility->devices);
        $unassignedDevices = DeviceResource::collection(Auth::user()->unassignedDevices());
        return response()->json([
            "devices" => $devices->values(),
            "unassignedDevices" => $unassignedDevices->values(),
            "healthFacility" => $healthFacility,
        ]);
    }

    //Assigns a device to the health facility 
    public function assignDevice(HealthFacility $healthFacility,Device $device){
        $this->authorize('assignDevice',[$healthFacility,$device]);
        $device = $this->healthFacilityService->assignDevice($healthFacility,$device);
        return response()->json(new DeviceResource($device));
    }

    //Unassign a device from the health facility
    public function unassignDevice(HealthFacility $healthFacility,Device $device){
        $this->authorize("unassignDevice",[$healthFacility,$device]);
        $device = $this->healthFacilityService->unassignDevice($healthFacility,$device);
        return response()->json(new DeviceResource($device));
    }

    //Returns the list of algorithms available at medal-creator as well as the given health facility
    public function manageAlgorithms(HealthFacility $healthFacility){
        $this->authorize('manageAlgorithms',$healthFacility);
        $algorithms = $this->algorithmService->getAlgorithmsMetadata();
        return response()->json([
            "algorithms" => $algorithms,
            "healthFacility" => $healthFacility,
        ]);
    }

    //Returns the algorithm version currently used by the health facility and the list of previously used versions
    public function accesses(HealthFacility $healthFacility){
        $this->authorize('accesses',$healthFacility);
        $currentAccess = $this->algorithmService->getCurrentAccess($healthFacility);
        $archivedAccesses = $this->algorithmService->getArchivedAccesses($healthFacility);
        return response()->json([
            "currentAccess" => $currentAccess,
            "archivedAccesses" => $archivedAccesses,
        ]);
    }

    //Fetches the list of versions for a specific algorithm from the medal-creator and returns it
    public function versions($algorithmCreatorID){
        $versions = $this->algorithmService->getVersionsMetadata($algorithmCreatorID);
        return response()->json($versions);
    }

    //Fetches the version indexed by versionID from the medal-creator and assigns it to the resolved health facility
    public function assignVersion(HealthFacility $healthFacility,$versionID){
        $this->authorize('assignVersion',$healthFacility);
        $versionJSON = $this->algorithmService->assignVersionToHealthFacility($healthFacility,$versionID);
        return response()->json([
            "message" => "Version Assigned",
            "id" => $versionID,
        ]);
        
    }

    //Since the original health_facilities table was created with bad non-null column, this assigns default values to them
    private function addDefaultValues(HealthFacility $healthFacility){
        $healthFacility->group_id = 1;
        $healthFacility->facility_name = "not used anymore";
    }
}
