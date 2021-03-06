<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Coordinates implements Rule
{
    /**
     * The regex responsible for checking the value.
     *
     * @var string
     */
    private string $pattern = "/^(-?\d+(\.\d+)?).\s*(-?\d+(\.\d+)?)$/";

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
        try {
            return preg_match($this->pattern, $value);
        } catch (\Throwable $th) {
            // We dont throw but we return false. This way, the parent validator
            // instance can handle the error and display correctly
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be a valid set of latitude and longitude coordinates.';
    }
}
