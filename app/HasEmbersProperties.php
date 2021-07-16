<?php

namespace App;

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
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function checkIfPropertiesBelongToTemplate(array $validated): void
    {
        $templateId = Arr::get($validated, 'template_id');

        $templateProperties = TemplateProperty::whereTemplateId($templateId)->get();

        $properties = Arr::get($validated, 'sink.data');

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

            if (!$flag) {
                $dotName = '';

                if (Arr::has($validated, 'sink')) $dotName = "sink.data.$name";
                if (Arr::has($validated, 'source')) $dotName = "source.data.$name";

                $errors->put($dotName, "Property $name is not valid.");
            }
        }

        if ($errors->isNotEmpty()) throw ValidationException::withMessages($errors->all());
    }
}
