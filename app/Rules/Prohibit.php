<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Arr;

class Prohibit implements Rule
{
    /**
     * The array that is holding request's data
     *
     * @var array $input
     */
    private array $input;

    /**
     * The field name that is prohibited under the current request
     *
     * @var string $prohibited
     */
    private string $prohibited;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(array $input, string $prohibited)
    {
        $this->input = $input;
        $this->prohibited = $prohibited;
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
        // if the value is present but is null, we dont care but if it exists and its not null, then we throw
        if (Arr::has($this->input, $this->prohibited) && Arr::get($this->input, $this->prohibited) !== null) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "Attribute :attribute cannot co-exist with $this->prohibited";
    }
}
