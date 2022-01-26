<?php

namespace App\Http\Controllers;

use App\Device;
use App\Services\DeviceService;
use App\Http\Requests\DeviceRequest;
use App\Http\Resources\Device as DeviceResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeviceController extends Controller
{

    protected $deviceService;

    public function __construct(DeviceService $deviceService)
    {
        $this->deviceService = $deviceService;
        $this->authorizeResource(Device::class);

    }

    public function manageTokens($id)
    {
        $device = Device::find($id);
        $user = Auth::user();
        $tokens = $user->tokens;

        $tokens = $tokens->filter(function ($token, $key) use ($device) {
            return $token->client_id == $device->oauth_client_id && $token->revoked == false;
        });

        return response()->json([
            "nbTokens" => $tokens->count(),
            "deviceName" => $device->name
        ]);
    }

    public function revokeTokens($id)
    {
        $device = Device::find($id);
        $user = Auth::user();
        $tokens = $user->tokens;

        $tokens = $tokens->filter(function ($token, $key) use ($device) {
            return $token->client_id == $device->oauth_client_id;
        });

        foreach ($tokens as $token) {
            $this->revokeAccessAndRefreshTokens($token->id);
        }

        return response()->json([
            "message" => "revoked tokens",
        ]);
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
        Log::info("User with id " . Auth::user()->id . " created a new device.", ["device" => $device]);
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
        $old_device = clone $device;
        $validated = $request->validated();
        $device = $this->deviceService->update($validated, $device);
        Log::info("User with id " . Auth::user()->id . " updated a device.", ["old_device" => $old_device, "new_device" => $device]);
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


//        // Delete corresponding client
//        $id = $this->deviceService->remove($device);
//        DB::table('oauth_clients')->where('name', '=', $device->name)->delete();


        return response()->json([
            "message" => "Deleted",
        ], 401);
    }

    private function revokeAccessAndRefreshTokens($tokenId) {
        $tokenRepository = app('Laravel\Passport\TokenRepository');
        $refreshTokenRepository = app('Laravel\Passport\RefreshTokenRepository');

        $tokenRepository->revokeAccessToken($tokenId);
        $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($tokenId);
    }
}
