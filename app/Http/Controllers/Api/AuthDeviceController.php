<?php

namespace App\Http\Controllers\Api;

use App\Device;
use App\Http\Controllers\Controller;
use App\Http\Requests\DeviceInfoRequest;
use App\Http\Resources\Device as DeviceResource;
use App\Services\AlgorithmService;
use App\Services\DeviceService;
use Illuminate\Http\Request;
use ZipArchive;

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

    public function healthFacilityInfo(Request $request, Device $device)
    {
        $info = $this->deviceService->getHealthFacilityInfo($device);
        return response()->json($info);
    }

    public function storeDeviceInfo(DeviceInfoRequest $request, Device $device)
    {
        $validated = $request->validated();
        $this->deviceService->storeDeviceInfo($device, $validated);
        return response()->json(new DeviceResource($device));
    }

    public function algorithm(Request $request, Device $device)
    {
        $json_version = (int) $request->get("json_version");
        $alg = $this->algorithmService->getAlgorithmJsonForDevice($device);

        if ($json_version < $alg["json_version"] || $json_version == null) {
            $zip = new ZipArchive();
            $res = $zip->open('algo.zip', ZipArchive::CREATE);
            if ($res === true) {
                $zip->addFromString('content.json', json_encode($alg["algo"]->medal_r_json));
            }
            $zip->close();
            return response()->download("algo.zip")->deleteFileAfterSend();
        } else {
            return response()->noContent();
        }
    }

    public function emergencyContent(Request $request, Device $device)
    {
        $json_version = (int) $request->get("json_version");
        $emergencyContent = $this->algorithmService->getAlgorithmEmergencyContentJsonForDevice($device);

        if ($json_version < $emergencyContent["json_version"] || $json_version == null) {
            $zip = new ZipArchive();
            $res = $zip->open('emergency.zip', ZipArchive::CREATE);
            if ($res === true) {
                $zip->addFromString('content.json', json_encode($emergencyContent["emergency_content"]));
            }
            $zip->close();
            return response()->download("emergency.zip")->deleteFileAfterSend();
        } else {
            return response()->noContent();
        }
    }
}
