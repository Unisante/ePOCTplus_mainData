<?php

namespace App\Http\Controllers;

use App\HealthFacility;

class FacilitiesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(HealthFacility::class);
    }

    public function index()
    {
        $facilities = HealthFacility::whereHas('patients')
            ->withCount('patients')
            ->withCount('patients_medical_cases')
            ->with(['patients_medical_cases' => function ($query) {
                $query->orderBy('updated_at', 'desc');
            }])
            ->get();

        return view('facilities.index')->with('facilities', $facilities);
    }
}
