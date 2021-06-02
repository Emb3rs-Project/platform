<?php

namespace App\Actions\Embers\Objects\Sinks;

use App\Contracts\Embers\Objects\Sinks\CreatesSinks;
use App\EmbersPermissionable;
use App\Models\Category;
use App\Models\Location;
use App\Models\Template;

class CreateSink implements CreatesSinks
{
    use EmbersPermissionable;

    /**
     * Display the necessary objects for the creation of a Sink.
     *
     * @param  mixed  $user
     * @return mixed
     */
    public function create($user)
    {
        $this->authorize($user);

        $sinkCategories = Category::whereType('sink')->get()->pluck('id');

        $equipmentCategories = Category::whereType('equipment')->get()->pluck('id');

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
