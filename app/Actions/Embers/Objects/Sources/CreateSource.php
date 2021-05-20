<?php

namespace App\Actions\Embers\Objects\Sources;

use App\Contracts\Embers\Objects\Sources\CreatesSources;
use App\Models\Category;
use App\Models\Instance;
use App\Models\Location;
use App\Models\Template;
use Illuminate\Support\Facades\Gate;

class CreateSource implements CreatesSources
{
    /**
     * Display the necessary objects for the creation of a Source.
     *
     * @return mixed
     */
    public function create()
    {
        Gate::authorize('create', Instance::class);

        $sourceCategories = Category::whereType('source')
            ->get()
            ->pluck('id');

        $equipmentCategories = Category::whereType('equipment')
            ->get();

        $processCategories = Category::whereType('process')
            ->get();

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

        $locations = Location::with(['geoObject'])->get();

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
