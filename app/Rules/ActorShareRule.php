<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ActorShareRule implements Rule
{

    public function passes($attribute, $value)
    {
        $sum = collect($value)->sum();
        return $sum <= 1;
    }

    public function message()
    {
        return 'The actor share sum can\'t be greater than 1';
    }
}
