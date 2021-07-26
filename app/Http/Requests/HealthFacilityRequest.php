<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class HealthFacilityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => 'required|string',
            'country' => 'required|string',
            'area' => 'required|string',
            'pin_code' => 'required|integer',
            'hf_mode' => [Rule::in(['standalone','client-server'])],
            'local_data_ip' => 'nullable|string|ip',
            'lat' => 'numeric | between:-90,90',
            'long' => 'numeric | between:-180,180',
        ];
        return $rules;
    }
}
