<?php

namespace App\Actions\Embers\Objects\Sources;

use App\Contracts\Embers\Objects\Sources\ShowsSources;
use App\EmbersPermissionable;
use App\Models\Instance;
use App\Models\Template;
use Illuminate\Support\Arr;

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

        $source = Instance::with([
            'template',
            'template.templateProperties.property',
            'template.templateProperties.unit',
            'location'
        ])->findOrFail($id);

        $equipment = Arr::get($source->values, 'equipment') ?? [];

        $processes = Arr::get($source->values, 'processes') ?? [];

        $equipmentTemplates = Template::whereIn('id', Arr::pluck($equipment, 'id'))
            ->with([
                'templateProperties',
                'templateProperties.property',
                'templateProperties.unit',
            ])
            ->get();

        $processesTemplates = Template::whereIn('id', Arr::pluck($processes, 'id'))
            ->with([
                'templateProperties',
                'templateProperties.property',
                'templateProperties.unit',
            ])
            ->get();

        return [
            $source,
            $equipmentTemplates,
            $processesTemplates
        ];
    }
}
