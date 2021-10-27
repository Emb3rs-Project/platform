<?php

namespace App\Actions\Embers\Objects\Sources;

use App\Contracts\Embers\Objects\Sources\EditsSources;
use App\EmbersPermissionable;
use App\Models\Category;
use App\Models\Instance;
use App\Models\Location;
use App\Models\Template;
use App\Models\User;
use Illuminate\Support\Arr;

class EditSource implements EditsSources
{
    use EmbersPermissionable;

    /**
     * Display the necessary objects for updating a given Source.
     *
     * @param  \App\Models\User  $user
     * @param  int  $id
     * @return array
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function edit(User $user, int $id): array
    {
        $this->authorize($user);

        $source = Instance::query()
            ->with(['location', 'template', 'template.category'])
            ->findOrFail($id);

        $locations = Location::query()->get();

        $sourceCategories = Category::query()->whereType('source')->get('id');

        $equipmentCategories = Category::query()->whereType('equipment')->get();

        $processCategories = Category::query()->whereType('process')->get();

        $sourceTemplates = Template::query()
            ->whereIn('category_id', $sourceCategories)
            ->with([
                'templateProperties',
                'templateProperties.unit',
                'templateProperties.property'
            ])
            ->get();

        $equipmentTemplates = Template::query()
            ->whereIn('category_id', Arr::pluck($equipmentCategories, 'id'))
            ->with([
                'templateProperties',
                'templateProperties.unit',
                'templateProperties.property'
            ])
            ->get();

        $processTemplates = Template::query()
            ->whereIn('category_id', Arr::pluck($processCategories, 'id'))
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
