<?php

namespace App\Http\Requests;

use App\Rules\MacAddress;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class DeviceRequest extends FormRequest
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
        return [
            'name' => 'required | string',
            'type' => ['required','string',Rule::in(['reader','hub'])],
            'mac_address' => ['nullable','string',new MacAddress],
            'model' => 'nullable | string',
            'brand' => 'nullable | string',
            'os' => 'nullable | string',
            'os_version' => 'nullable | string',
            'status' => 'nullable | integer | between:0,1',
        ];
    }
}
