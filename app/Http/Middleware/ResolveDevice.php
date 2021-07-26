<?php

namespace App\Http\Middleware;

use Closure;
use App\Device;
use App\Services\DeviceService;

class ResolveDevice
{


    protected $deviceService;

    public function __construct(DeviceService $deviceService)
    {
        $this->deviceService = $deviceService;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   
        $device = $this->deviceService->getDeviceFromAuthRequest($request);
        $device->last_seen = now();
        $device->save();
        app()->instance(Device::class, $device);
        return $next($request);
    }
}
