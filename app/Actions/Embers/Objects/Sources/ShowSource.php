<?php

namespace App\Actions\Embers\Objects\Sources;

use App\Contracts\Embers\Objects\Sources\ShowsSources;
use App\EmbersPermissionable;
use App\Models\Instance;
use App\Models\Template;
use App\Models\User;
use Illuminate\Support\Arr;

class ShowSource implements ShowsSources
{
    use EmbersPermissionable;

    /**
     * Find and return an existing Source.
     *
     * @param  \App\Models\User  $user
     * @param  int  $id
     * @return array
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function show(User $user, int $id): array
    {
        $this->authorize($user);

        $source = Instance::query()
            ->with([
                'template',
                'template.templateProperties.property',
                'template.templateProperties.unit',
                'location'
            ])->findOrFail($id);

        $equipment = Arr::get($source->values, 'equipment') ?? [];

        $processes = Arr::get($source->values, 'processes') ?? [];

        $equipmentTemplates = Template::query()
            ->whereIn('id', Arr::pluck($equipment, 'id'))
            ->with([
                'templateProperties',
                'templateProperties.property',
                'templateProperties.unit',
            ])
            ->get();

        $processesTemplates = Template::query()
            ->whereIn('id', Arr::pluck($processes, 'id'))
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
