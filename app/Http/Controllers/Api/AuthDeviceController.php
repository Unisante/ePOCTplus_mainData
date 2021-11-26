<?php

namespace App\Http\Controllers\Api;

use App\Device;
use Illuminate\Http\Request;
use App\Services\DeviceService;
use App\Services\AlgorithmService;
use App\Http\Controllers\Controller;
use App\Http\Requests\DeviceInfoRequest;
use App\Http\Resources\Device as DeviceResource;

class AuthDeviceController extends Controller
{
    //
    protected $deviceService;
    protected $algorithmService;

    public function __construct(DeviceService $deviceService, AlgorithmService $algorithmService)
    {
        $this->deviceService = $deviceService;
        $this->algorithmService = $algorithmService;
    }

    public function healthFacilityInfo(Request $request,Device $device){
        $info = $this->deviceService->getHealthFacilityInfo($device);
        return response()->json($info);
    }

    public function storeDeviceInfo(DeviceInfoRequest $request, Device $device){
        $validated = $request->validated();
        $this->deviceService->storeDeviceInfo($device,$validated);
        return response()->json(new DeviceResource($device));
    }

    public function algorithm(Request $request, Device $device) {
        $json_version = (int)$request->get("json_version");
        $alg = $this->algorithmService->getAlgorithmJsonForDevice($device);

        if ($json_version < $alg["json_version"] || $json_version == null) {
            return response()->json($alg["algo"]);
        } else {
            return response()->noContent();
        }
    }

    public function emergencyContent(Request $request,Device $device) {
        $json_version = (int)$request->get("json_version");
        $emergencyContent = $this->algorithmService->getAlgorithmEmergencyContentJsonForDevice($device);

        if ($json_version < $emergencyContent["json_version"] || $json_version == null) {
            return response()->json($emergencyContent["emergency_content"]);
        } else {
            return response("", 204);
        }
    }

}
