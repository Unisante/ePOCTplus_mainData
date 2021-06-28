<?php

namespace App\Http\Requests;

use App\Rules\MacAddress;
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
            'mac_address' => ['string',new MacAddress],
            'model' => 'string',
            'brand' => 'string',
            'os' => 'string',
            'os_version' => 'string',
            'status' => 'integer | between:0,1',
        ];
    }
}
