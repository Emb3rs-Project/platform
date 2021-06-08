<?php

namespace App\Actions\Embers\Objects\Sources;

use App\Contracts\Embers\Objects\Sources\EditsSources;
use App\EmbersPermissionable;
use App\Models\Category;
use App\Models\Instance;
use App\Models\Location;
use App\Models\Template;

class EditSource implements EditsSources
{
    use EmbersPermissionable;

    /**
     * Display the necessary objects for updating a given Source.
     *
     * @param  mixed  $user
     * @param  int  $id
     * @return mixed
     */
    public function edit($user, int $id)
    {
        $this->authorize($user);

        $source = Instance::with(['location', 'template', 'template.category'])->findOrFail($id);

        $sourceCategories = Category::whereType('source')->get()->pluck('id');

        $equipmentCategories = Category::whereType('equipment')->get()->pluck('id');

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
