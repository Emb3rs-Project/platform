<?php

namespace App\Actions\Embers\Objects\Sources;

use App\Contracts\Embers\Objects\Sources\EditsSources;
use App\Models\Category;
use App\Models\Instance;
use App\Models\Location;
use App\Models\Template;
use Illuminate\Support\Facades\Gate;

class EditSource implements EditsSources
{
    /**
     * Display the necessary objects for updating a given Source.
     *
     * @param  int  $id
     * @return mixed
     */
    public function edit(int $id)
    {
        $source = Instance::with(['location', 'template', 'template.category'])->findOrFail($id);

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

        $locations = Location::all();

        return [
            $sourceTemplates,
            $equipmentTemplates,
            $locations,
            $source
        ];
    }
}
