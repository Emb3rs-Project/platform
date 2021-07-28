<?php

namespace App;

use App\Models\Property;
use App\Models\Template;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

trait HasEmbersProperties
{
    /**
     * Check if the provided Properties belong to the provided Template
     *
     * @param  array  $validated
     * @param  int  $templateId
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function checkIfPropertiesAreValid(array $validated, ?int $templateId = null): void
    {
        $instanceType = $this->getInstanceType($validated);

        $properties = Arr::get($validated, "$instanceType.data");

        $templateId = Arr::get($validated, 'template_id') ?? $templateId;

        $templateProperties = Template::find($templateId)->templateProperties;

        $errors = new Collection();

        foreach ($properties as $field => $value) {
            $flag = false;

            foreach ($templateProperties as $templateProperty) {
                $propertyId = $templateProperty->property_id;

                $property = Property::find($propertyId);

                if ($property->symbolic_name === $field) {
                    $flag = true;

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

                    $validator = Validator::make([$field => $value], [
                        "$field" => [
                            Rule::requiredIf(fn () => $templateProperty->required),
                            $type,
                            'nullable'
                        ]
                    ]);

                    if ($validator->fails()) {
                        $messages = $validator->errors()->all();

                        $errors->put("$instanceType.data.$field", Arr::flatten($messages));
                    }
                };
            }

            if (!$flag) $errors->put("$instanceType.data.$field", "Property $field is not valid.");
        }

        if ($errors->isNotEmpty()) throw ValidationException::withMessages($errors->all());
    }

    /**
     * Determine the Instance type of the calling class
     *
     * @param  array  $validated
     * @return string
     */
    private function getInstanceType(array $validated): string
    {
        return Arr::has($validated, 'sink') ? 'sink' : 'source';
    }
}
