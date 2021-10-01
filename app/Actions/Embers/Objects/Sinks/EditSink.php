<?php

namespace App\Actions\Embers\Objects\Sinks;

use App\Contracts\Embers\Objects\Sinks\EditsSinks;
use App\EmbersPermissionable;
use App\Models\Category;
use App\Models\Instance;
use App\Models\Location;
use App\Models\Template;
use App\Models\User;

class EditSink implements EditsSinks
{
    use EmbersPermissionable;

    /**
     * Display the necessary objects for updating a given Sink.
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

        $sink = Instance::query()
            ->with(['location', 'template', 'template.category'])
            ->findOrFail($id);

        $sinkCategories = Category::query()->whereType('sink')->get('id');

        $sinkTemplates = Template::query()
            ->whereIn('category_id', $sinkCategories)
            ->with([
                'templateProperties',
                'templateProperties.unit',
                'templateProperties.property'
            ])
            ->get();

        $locations = Location::query()->all();

        return [
            $sinkTemplates,
            $locations,
            $sink
        ];
    }
}
