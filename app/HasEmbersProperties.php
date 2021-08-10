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
     * Determine the Instance type of the calling class
     *
     * @param  array  $validated
     * @return string
     */
    private function getInstanceType(array $validated): string
    {
        return Arr::has($validated, 'sink') ? 'sink' : 'source';
    }

    /**
     * Validate the properties
     *
     * @param  string $instanceType
     * @param  int|null  $index
     * @param  array  $properties
     * @param  \Illuminate\Database\Eloquent\Collection<mixed, \App\Models\TemplateProperty>  $templateProperties
     * @param  \Illuminate\Support\Collection  $errors
     * @return \Illuminate\Support\Collection
     */
    private function validateProperties(string $instanceType, ?int $index, array $properties, $templateProperties, Collection &$errors)
    {
        foreach ($properties as $field => $value) {
            $key = !is_null($index) ? "$instanceType.$index.data.$field" : "$instanceType.data.$field";

            $property = Property::whereSymbolicName($field)->first();

            if (is_null($property)) {
                $errors->put($key, "Property $field does not exist.");

                continue;
            }

            $templateProperty = $templateProperties->firstWhere('property_id', $property->id);

            if (is_null($templateProperty)) {
                $errors->put($key, "Property $field does not belong to this template.");

                continue;
            }

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
                    $type = 'date_format:d/m/Y';
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

                $errors->put($key, Arr::flatten($messages));
            }
        }
    }

    /**
     * Check if the provided Properties are valid.
     *
     * @param  array  $validated
     * @param  int  $templateId
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function checkIfPropertiesAreValid(array $validated, int $templateId = null): void
    {
        $errors = new Collection();

        $instanceType = $this->getInstanceType($validated);

        $properties = Arr::get($validated, "$instanceType.data");

        $templateId = Arr::get($validated, 'template_id') ?? $templateId;

        $template = Template::find($templateId);

        $templateProperties = Template::find($templateId)->templateProperties;

        if ($template->category->type !== $instanceType)
            $errors->put('template_id', 'Property template_id is not a valid template.');

        $this->validateProperties($instanceType, null, $properties, $templateProperties, $errors);

        if ($errors->isNotEmpty()) throw ValidationException::withMessages($errors->all());
    }

    /**
     * Check if the provided Properties belong to the provided Template
     *
     * @param  array  $validated
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function checkIfEquipmentPropertiesAreValid(array $validated): void
    {
        $equipments = Arr::get($validated, 'equipments');

        if (!Arr::accessible($equipments)) return;

        $errors = new Collection();

        foreach ($equipments as $index => $equipment) {
            $instanceType = $this->getInstanceType($validated);

            $templateId = Arr::get($equipment, 'id');

            $template = Template::find($templateId);

            $categoryId = Arr::get($equipment, 'category_id');

            $properties = Arr::get($equipment, 'data');

            $templateProperties = Template::find($templateId)->templateProperties;

            if ($template->category->type !== 'equipment')
                $errors->put(
                    "$instanceType.equipments.$index.id",
                    "Property $instanceType.equipments.$index.id is not a valid template."
                );

            if ($template->category->id !== $categoryId)
                $errors->put(
                    "$instanceType.equipments.$index.category_id",
                    "Property $instanceType.equipments.$index.category_id is not valid."
                );

            $this->validateProperties("$instanceType.equipments", $index, $properties, $templateProperties, $errors);
        }

        if ($errors->isNotEmpty()) throw ValidationException::withMessages($errors->all());
    }

    /**
     * Check if the provided Properties belong to the provided Template
     *
     * @param  array  $validated
     * @param  int  $templateId
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function checkIfProcessPropertiesAreValid(array $validated, ?int $templateId = null): void
    {
        //
    }
}
