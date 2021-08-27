<?php

namespace App\Actions\Embers\Objects\Sources;

use App\Contracts\Embers\Objects\Sources\EditsSources;
use App\EmbersPermissionable;
use App\Models\Category;
use App\Models\Instance;
use App\Models\Location;
use App\Models\Template;
use Illuminate\Support\Arr;

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
        $locations = Location::all();

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

        $equipmentTemplates = Template::whereIn('category_id', Arr::pluck($equipmentCategories, 'id'))
            ->with([
                'templateProperties',
                'templateProperties.unit',
                'templateProperties.property'
            ])
            ->get();

        $processTemplates = Template::whereIn('category_id', Arr::pluck($processCategories, 'id'))
            ->with([
                'templateProperties',
                'templateProperties.unit',
                'templateProperties.property'
            ])
            ->get();

        return [
            $source,
            $sourceTemplates,
            $locations,
            $equipmentTemplates,
            $equipmentCategories,
            $processTemplates,
            $processCategories,
        ];
    }
}
