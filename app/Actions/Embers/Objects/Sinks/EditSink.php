<?php

namespace App\Actions\Embers\Objects\Sinks;

use App\Contracts\Embers\Objects\Sinks\EditsSinks;
use App\EmbersPermissionable;
use App\Models\Category;
use App\Models\Instance;
use App\Models\Location;
use App\Models\Template;

class EditSink implements EditsSinks
{
    use EmbersPermissionable;

    /**
     * Display the necessary objects for updating a given Sink.
     *
     * @param  mixed  $user
     * @param  int  $id
     * @return mixed
     */
    public function edit($user, int $id)
    {
        $this->authorize($user);

        $sink = Instance::with(['location', 'template', 'template.category'])->findOrFail($id);

        $sinkCategories = Category::whereType('sink')->get('id');

        $equipmentCategories = Category::whereType('equipment')->get('id');

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
            $locations,
            $sink
        ];
    }
}
