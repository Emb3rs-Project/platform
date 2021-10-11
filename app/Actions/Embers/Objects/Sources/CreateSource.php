<?php

namespace App\Actions\Embers\Objects\Sources;

use App\Contracts\Embers\Objects\Sources\CreatesSources;
use App\EmbersPermissionable;
use App\Models\Category;
use App\Models\Location;
use App\Models\Template;
use App\Models\User;

class CreateSource implements CreatesSources
{
    use EmbersPermissionable;

    /**
     * Display the necessary objects for the creation of a Source.
     *
     * @param  \App\Models\User  $user
     * @return array
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    public function create(User $user): array
    {
        $this->authorize($user);

        $sourceCategories = Category::query()->whereType('source')->get('id');

        $equipmentCategories = Category::query()->whereType('equipment')->get();

        $processCategories = Category::query()->whereType('process')->get();

        $sourceTemplates = Template::query()
            ->whereIn('category_id', $sourceCategories)
            ->with([
                'templateProperties',
                'templateProperties.unit',
                'templateProperties.property'
            ])
            ->get();

        $equipmentTemplates = Template::query()
            ->whereIn('category_id', $equipmentCategories->map(fn ($e) => $e->id))
            ->with([
                'templateProperties',
                'templateProperties.unit',
                'templateProperties.property'
            ])
            ->get();

        $locations = Location::query()->get();

        $processTemplates = Template::query()
            ->whereIn('category_id', $processCategories->map(fn ($p) => $p->id))
            ->with([
                'templateProperties',
                'templateProperties.unit',
                'templateProperties.property'
            ])
            ->get();

        return [
            $sourceTemplates,
            $equipmentTemplates,
            $equipmentCategories,
            $processTemplates,
            $processCategories,
            $locations,
        ];
    }
}
