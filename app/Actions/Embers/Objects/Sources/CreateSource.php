<?php

namespace App\Actions\Embers\Objects\Sources;

use App\Contracts\Embers\Objects\Sources\CreatesSources;
use App\EmbersPermissionable;
use App\Models\Category;
use App\Models\Location;
use App\Models\Template;
use Illuminate\Support\Arr;

class CreateSource implements CreatesSources
{
    use EmbersPermissionable;

    /**
     * Display the necessary objects for the creation of a Source.
     *
     * @param  mixed  $user
     * @return mixed
     */
    public function create($user)
    {
        $this->authorize($user);

        $sourceCategories = Category::whereType('source')->get('id');

        $equipmentCategories = Category::whereType('equipment')->get();

        $processCategories = Category::whereType('process')->get();

        $sourceTemplates = Template::whereIn('category_id', $sourceCategories)
            ->with([
                'templateProperties',
                'templateProperties.unit',
                'templateProperties.property'
            ])
            ->get();

        $equipmentTemplates = Template::whereIn('category_id', $equipmentCategories->map(fn ($e) => $e->id))
            ->with([
                'templateProperties',
                'templateProperties.unit',
                'templateProperties.property'
            ])
            ->get();

        $locations = Location::all();

        $processTemplates = Template::whereIn('category_id', $processCategories->map(fn ($p) => $p->id))
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
