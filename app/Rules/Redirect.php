<?php

namespace App\Rules;

use Illuminate\Support\Facades\Config;
use Illuminate\Contracts\Validation\Rule;

class Redirect implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $device_type = request()->get('type');
        return $device_type == 'reader' || (request()->has('redirect') && request()->filled('redirect'));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'A redirect URL should be provided for hub-devices';
    }
}
