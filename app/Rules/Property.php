<?php

namespace App\Rules;

use App\Models\Property as ModelsProperty;
use App\Models\TemplateProperty;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule as ValidationRule;

class Property implements Rule
{
    /**
     * The validation error messages
     *
     * @var \Illuminate\Support\Collection
     */
    private Collection $messages;
    // private array $messages;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->messages = new Collection();

        // Generic catch-all message
        $this->messages->push('An error occured during the validation');
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
            case 'text': // THIS IS FOR LEAGACY SUPPORT FOR THE EXISTING RECORDS
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
            case 'datetime':
                $type = 'numeric';
                break;

            default:
                break;
        }

        $validator = Validator::make([
            $attribute => $value
        ], [
            $attribute => [
                ValidationRule::requiredIf(function () use ($templateProperty) {
                    return $templateProperty->required;
                }),
                $type
            ]
        ]);

        if ($validator->fails()) {
            $errors = collect($validator->errors()->all());

            $this->messages = $errors;

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
        $message = $this->messages->implode(PHP_EOL);

        return $message;
    }
}
