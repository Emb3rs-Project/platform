<?php

namespace App\Actions\Embers\Objects\Sources;

use App\Contracts\Embers\Objects\Sources\ShowsSources;
use App\Models\Category;
use App\Models\Instance;
use App\Models\Location;
use App\Models\Template;
use Illuminate\Support\Facades\Gate;

class ShowSource implements ShowsSources
{
    /**
    * Find and return an existing Source.
    *
    * @param mixed  $user
    * @param int    $id
    * @return mixed
    */
    public function show(mixed $user, int $id)
    {
        $source = Instance::findOrFail($id);

        Gate::authorize('view', $source);

        $sourceCategories = Category::whereType('source')
            ->get()
            ->pluck('id');

        $equipmentCategories = Category::whereType('equipment')
            ->get()
            ->pluck('id');

        $sourceTemplates = Template::whereIn('category_id', $sourceCategories)
            ->with([
                'templateProperties',
                'templateProperties.unit',
                'templateProperties.property'
            ])
            ->get();

        $equipmentTemplates = Template::whereIn('category_id', $equipmentCategories)
            ->with([
                'templateProperties',
                'templateProperties.unit',
                'templateProperties.property'
            ])
            ->get();

        $locations = Location::with(['geoObject'])->get();

        $instance = Instance::whereId($id)
            ->with([
                'location',
                'template',
                'template.category',
                'location.geoObject'
            ])
            ->first();

        return [
            $sourceTemplates,
            $equipmentTemplates,
            $locations,
            $instance
        ];
    }
}
