<?php

namespace App\Http\Requests;

use App\Rules\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class MedicalStaffRequest extends FormRequest
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
            'first_name' => 'required | string',
            'last_name' => 'required | string',
            'medical_staff_role_id' => 'required | integer'
        ];
    }
}
