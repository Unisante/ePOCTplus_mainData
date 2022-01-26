<?php

namespace App\Http\Controllers;

use App\Services\MedicalStaffService;
use App\Http\Requests\MedicalStaffRequest;
use App\Http\Resources\MedicalStaff as MedicalStaffResource;
use App\Http\Resources\MedicalStaffRole as MedicalStaffRoleResource;
use App\MedicalStaff;
use App\MedicalStaffRole;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class MedicalStaffController extends Controller
{
    protected $medicalStaffService;

    public function __construct(MedicalStaffService $medicalStaffService)
    {
        $this->medicalStaffService = $medicalStaffService;
        $this->authorizeResource(MedicalStaff::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $medical_staff = MedicalStaffResource::collection(MedicalStaff::all());
        $medical_staff_roles = MedicalStaffRoleResource::collection(MedicalStaffRole::all());

        // Avoid out of date data for next querries
        Cache::forget('health_facilities');
        Cache::forget('roles');

        return view('medicalStaff.index', [
            'medical_staff' => $medical_staff->toJson(),
            'medical_staff_roles' => $medical_staff_roles->toJson()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('medicalStaff.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MedicalStaffRequest $request)
    {
        $validated = $request->validated();
        $medical_staff = $this->medicalStaffService->add($validated);
        return response()->json(new MedicalStaffResource($medical_staff));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MedicalStaff  $medical_staff
     * @return \Illuminate\Http\Response
     */
    public function update(MedicalStaffRequest $request, MedicalStaff $medical_staff)
    {
        $validated = $request->validated();
        $med_staff = $this->medicalStaffService->update($validated, $medical_staff);
        return response()->json(new MedicalStaffResource($med_staff));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MedicalStaff  $device
     * @return \Illuminate\Http\Response
     */
    public function destroy(MedicalStaff $medical_staff)
    {
        $id = $this->medicalStaffService->remove($medical_staff);
        return response()->json([
            "message" => "Deleted",
            "id" => $id,
        ]);
    }
}