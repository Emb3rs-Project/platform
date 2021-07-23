<?php

namespace App\Actions\Embers\Objects\Sources;

use App\Contracts\Embers\Objects\Sources\ShowsSources;
use App\EmbersPermissionable;
use App\Models\Category;
use App\Models\Instance;
use App\Models\Location;
use App\Models\Template;

class ShowSource implements ShowsSources
{
    use EmbersPermissionable;

    /**
     * Find and return an existing Source.
     *
     * @param  mixed  $user
     * @param  int  $id
     * @return mixed
     */
    public function show($user, int $id)
    {
        $this->authorize($user);

        $source = Instance::with(['location', 'template', 'template.category'])->findOrFail($id);

        $sourceCategories = Category::whereType('source')->get('id');

        $equipmentCategories = Category::whereType('equipment')->get('id');

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
