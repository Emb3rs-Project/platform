<?php

namespace App;

use App\Models\Instance;
use App\Models\Property;
use App\Models\TemplateProperty;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
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
    protected function checkIfPropertiesBelongToTemplate(array $validated, ?int $templateId): void
    {
        $instanceType = $this->getInstanceType($validated);

        $templateId = Arr::get($validated, 'template_id') ?? $templateId;

        $templateProperties = TemplateProperty::whereTemplateId($templateId)->get();

        $properties = Arr::get($validated, "$instanceType.data");

        $errors = new Collection();

        foreach ($properties as $name => $value) {
            $flag = false;

            foreach ($templateProperties as $templateProperty) {
                $propertyId = $templateProperty->property_id;

                $property = Property::find($propertyId);

                if ($property->symbolic_name === $name) {
                    $flag = true;

                    break;
                };
            }

            if (!$flag) $errors->put("$instanceType.data.$name", "Property $name is not valid.");
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
