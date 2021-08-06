<?php

namespace App\Http\Requests;

use App\Rules\MacAddress;
use Illuminate\Foundation\Http\FormRequest;

class DeviceInfoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'mac_address' => ['nullable','string',new MacAddress],
            'model' => 'nullable | string',
            'brand' => 'nullable | string',
            'os' => 'nullable | string',
            'os_version' => 'nullable | string',
        ];
    }
}
