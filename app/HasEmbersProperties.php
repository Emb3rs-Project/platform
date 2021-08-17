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

            $validator = Validator::make([$field => $value], [
                "$field" => [
                    Rule::requiredIf(fn () => $templateProperty->required),
                    'nullable'
                ]
            ]);

            match (Str::lower($property->dataType)) {
                'text' => $validator->addRules(["$field" => ['string']]),
                'string' => $validator->addRules(["$field" => ['string']]),
                'number' => $validator->addRules(["$field" => ['numeric', 'integer']]),
                'float' => $validator->addRules(["$field" => ['numeric', 'integer']]),
                'datetime' => $validator->addRules(["$field" => ['date_format:d/m/Y']]),
                default => abort(500, "DataType of property $property->name is not configured correctly."),
            };

            if ($validator->fails()) {
                $messages = $validator->errors()->all();

                $errors->put($key, Arr::flatten($messages));
            }
        }
    }

    /**
     * Check if the provided properties are valid.
     *
     * @param  array  $validated
     * @param  int|null  $templateId
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function checkIfPropertiesAreValid(array $validated, ?int $templateId = null): void
    {
        $errors = new Collection();

        $this->checkIfInstancePropertiesAreValid($validated, $templateId, $errors);

        $this->checkIfAttachedTemplatePropertiesAreValid('equipment', $validated, $errors);

        $this->checkIfAttachedTemplatePropertiesAreValid('processes', $validated, $errors);

        if ($errors->isNotEmpty()) throw ValidationException::withMessages($errors->all());
    }

    /**
     * Check if the provided instance properties are valid.
     *
     * @param  array  $validated
     * @param  int  $templateId
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function checkIfInstancePropertiesAreValid(array $validated, ?int $templateId, Collection &$errors): void
    {
        $instanceType = $this->getInstanceType($validated);

        $properties = Arr::get($validated, "$instanceType.data");

        $templateId = Arr::get($validated, 'template_id') ?? $templateId;

        $template = Template::find($templateId);

        $templateProperties = Template::find($templateId)->templateProperties;

        if ($template->category->type !== $instanceType) {
            $errors->put('template_id', 'Property template_id is not a valid template.');
        }

        $this->validateProperties($instanceType, $index = null, $properties, $templateProperties, $errors);
    }

    /**
     * Check if the provided properties belong to their provided templates. Also
     * check if the provided templates are valid.
     *
     * @param  array  $validated
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function checkIfAttachedTemplatePropertiesAreValid(string $entity, array $validated, Collection &$errors): void
    {
        $entityNameInPlural = Str::plural($entity);
        $entityNameInSingular = Str::singular($entity);

        $templates = Arr::get($validated, $entityNameInPlural);

        if (!Arr::accessible($templates)) return;

        foreach ($templates as $index => $template) {
            $templateId = Arr::get($template, 'id');

            $dbTemplate = Template::find($templateId);

            $categoryId = Arr::get($template, 'category_id');

            $properties = Arr::get($template, 'data');

            $templateProperties = Template::find($templateId)->templateProperties;

            if ($dbTemplate->category->type !== $entityNameInSingular)
                $errors->put(
                    "$entityNameInPlural.$index.id",
                    "Property id is not a valid template."
                );

            if ($dbTemplate->category->id !== $categoryId)
                $errors->put(
                    "$entityNameInPlural.$index.category_id",
                    "Property category_id is not valid."
                );

            $this->validateProperties($entityNameInPlural, $index, $properties, $templateProperties, $errors);
        }
    }
}
