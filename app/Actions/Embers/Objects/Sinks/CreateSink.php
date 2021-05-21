<?php

namespace App\Actions\Embers\Objects\Sinks;

use App\Contracts\Embers\Objects\Sinks\CreatesSinks;
use App\Models\Category;
use App\Models\Instance;
use App\Models\Location;
use App\Models\Template;
use Illuminate\Support\Facades\Gate;

class CreateSink implements CreatesSinks
{
    /**
     * Display the necessary objects for the creation of a Sink.
     *
     * @return mixed
     */
    public function create()
    {
        Gate::authorize('create', Instance::class);

        $sinkCategories = Category::whereType('sink')
            ->get()
            ->pluck('id');

        $equipmentCategories = Category::whereType('equipment')
            ->get()
            ->pluck('id');


        $sinkTemplates = Template::whereIn('category_id', $sinkCategories)
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

        $locations = Location::all();

        return [
            $sinkTemplates,
            $equipmentTemplates,
            $locations
        ];
    }
}
