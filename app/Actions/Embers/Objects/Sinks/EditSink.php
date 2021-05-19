<?php

namespace App\Actions\Embers\Objects\Sinks;

use App\Contracts\Embers\Objects\Sinks\EditsSinks;
use App\Models\Category;
use App\Models\Instance;
use App\Models\Location;
use App\Models\Template;
use Illuminate\Support\Facades\Gate;

class EditSink implements EditsSinks
{
    /**
     * Display the necessary objects for updating a given Sink.
     *
     * @param  mixed  $user
     * @param  int    $id
     * @param  array  $input
     * @return mixed
     */
    public function edit($user, int $id)
    {
        $sink = Instance::findOrFail($id);

        Gate::authorize('view', $sink);

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

        $locations = Location::with(['geoObject'])->get();

        $instance = Instance::whereId($id)
            ->with(['location', 'template', 'template.category', 'location.geoObject'])
            ->first();

        return [
            $sinkTemplates,
            $equipmentTemplates,
            $locations,
            $instance
        ];
    }
}
