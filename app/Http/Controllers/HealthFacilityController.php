<?php

namespace App\Http\Controllers;

use App\Device;
use App\HealthFacility;
use App\Http\Requests\HealthFacilityRequest;
use App\Http\Resources\Device as DeviceResource;
use App\Http\Resources\MedicalStaff as MedicalStaffResource;
use App\MedicalStaff;
use App\Services\AlgorithmService;
use App\Services\HealthFacilityService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class HealthFacilityController extends Controller
{
    protected $healthFacilityService;
    protected $algorithmService;

    public function __construct(HealthFacilityService $healthFacilityService,
        AlgorithmService $algorithmService) {
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
        $healthFacilities = HealthFacility::all();

        return view("healthFacilities.index", [
            "healthFacilities" => $healthFacilities,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HealthFacilityRequest $request)
    {
        $validated = $request->validate([
            'name' => 'required|string  | unique:App\HealthFacility,name',
            'country' => 'nullable|string',
            'area' => 'nullable|string',
            'pin_code' => 'nullable|integer',
            'hf_mode' => [Rule::in(['standalone', 'client-server'])],
            'local_data_ip' => 'nullable|string|ip',
            'lat' => 'numeric | between:-90,90',
            'long' => 'numeric | between:-180,180',
        ]);
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
        $validated = $request->validate([
            'name' => 'required|string  | unique:App\HealthFacility,name,' . $healthFacility->id,
            'country' => 'nullable|string',
            'area' => 'nullable|string',
            'pin_code' => 'nullable|integer',
            'hf_mode' => [Rule::in(['standalone', 'client-server'])],
            'local_data_ip' => 'nullable|string|ip',
            'lat' => 'numeric | between:-90,90',
            'long' => 'numeric | between:-180,180',
        ]);
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

    public function manageDevices(HealthFacility $healthFacility)
    {
        $devices = DeviceResource::collection($healthFacility->devices);
        $unassignedDevices = DeviceResource::collection(Device::where('health_facility_id', '=', null)->get());
        return response()->json([
            "devices" => $devices->values(),
            "unassignedDevices" => $unassignedDevices->values(),
            "healthFacility" => $healthFacility,
        ]);
    }

    public function assignDevice(HealthFacility $healthFacility, Device $device)
    {
        $device = $this->healthFacilityService->assignDevice($healthFacility, $device);
        return response()->json(new DeviceResource($device));
    }

    public function unassignDevice(HealthFacility $healthFacility, Device $device)
    {
        $device = $this->healthFacilityService->unassignDevice($healthFacility, $device);
        return response()->json(new DeviceResource($device));
    }

    public function manageAlgorithms(HealthFacility $healthFacility)
    {
        $algorithms = $this->algorithmService->getAlgorithmsMetadata();
        return response()->json([
            "algorithms" => $algorithms,
            "healthFacility" => $healthFacility,
        ]);
    }

    public function manageMedicalStaff(HealthFacility $health_facility)
    {
        $medical_staff = MedicalStaffResource::collection($health_facility->medical_staff);
        $unassigned_medical_staff = MedicalStaffResource::collection(MedicalStaff::whereNull('health_facility_id')->get());
        return response()->json([
            "health_facility" => $health_facility,
            "medical_staff" => $medical_staff,
            "unassigned_medical_staff" => $unassigned_medical_staff,
        ]);
    }

    public function assignMedicalStaff(HealthFacility $health_facility, MedicalStaff $medical_staff)
    {
        $medical_staff = $this->healthFacilityService->assignMedicalStaff($health_facility, $medical_staff);
        return response()->json(new MedicalStaffResource($medical_staff));
    }

    public function manageStickers(HealthFacility $health_facility)
    {
        return response()->json([
            "health_facility" => $health_facility,
        ]);
    }

    public function unassignMedicalStaff(HealthFacility $health_facility, MedicalStaff $medical_staff)
    {
        $medical_staff = $this->healthFacilityService->unassignMedicalStaff($health_facility, $medical_staff);
        return response()->json(new MedicalStaffResource($medical_staff));
    }

    public function accesses(HealthFacility $healthFacility)
    {
        $currentAccess = $this->algorithmService->getCurrentAccess($healthFacility);
        $archivedAccesses = $this->algorithmService->getArchivedAccesses($healthFacility);
        return response()->json([
            "currentAccess" => $currentAccess,
            "archivedAccesses" => $archivedAccesses,
        ]);
    }

    public function versions($algorithmCreatorID)
    {
        $versions = $this->algorithmService->getVersionsMetadata($algorithmCreatorID);
        return response()->json($versions);
    }

    public function assignVersion(HealthFacility $healthFacility, $chosenAlgorithmID, $versionID)
    {
        $this->algorithmService->assignVersionToHealthFacility($healthFacility, $chosenAlgorithmID, $versionID);
        return response()->json([
            "message" => "Version Assigned",
            "id" => $versionID,
        ]);
    }

    private function addDefaultValues(HealthFacility $healthFacility)
    {
        $healthFacility->group_id = 1;
        $healthFacility->facility_name = "not used anymore";
    }
}
