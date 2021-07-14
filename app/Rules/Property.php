<?php

namespace App\Rules;

use App\Models\Property as ModelsProperty;
use App\Models\TemplateProperty;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class Property implements Rule
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
        $attribute = Str::afterLast($attribute, '.');

        $property = ModelsProperty::whereSymbolicName($attribute)->first();

        if ($property->doesntExist()) return false;

        $templateProperty = TemplateProperty::wherePropertyId($property->id)->first();

        if ($templateProperty->doesntExist()) return false;

        $type = '';

        switch (Str::lower($property->dataType)) {
            case 'text':
                $type = 'string';
                break;
            case 'string':
                $type = 'string';
                break;
            case 'number':
                $type = 'numeric';
                break;
            case 'float':
                $type = 'numeric';
                break;

            default:
                break;
        }

        $validator = Validator::make([
            $attribute => $value
        ], [
            $attribute => [
                $templateProperty->required ?: 'required',
                $type
            ]
        ]);

        info($templateProperty->required);

        $t = $templateProperty->required ? 'required' : '';

        info("$t, $type");

        if ($validator->fails()) return false;

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
