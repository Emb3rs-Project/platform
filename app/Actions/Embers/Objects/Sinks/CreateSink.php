<?php

namespace App\Actions\Embers\Objects\Sinks;

use App\Contracts\Embers\Objects\Sinks\CreatesSinks;
use App\EmbersPermissionable;
use App\Models\Category;
use App\Models\Location;
use App\Models\Template;
use App\Models\User;

class CreateSink implements CreatesSinks
{
    use EmbersPermissionable;

    /**
     * Display the necessary objects for the creation of a Sink.
     *
     * @param  \App\Models\User  $user
     * @return array
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    public function create(User $user): array
    {
        $this->authorize($user);

        $sinkCategories = Category::query()->whereType('sink')->get('id');

        $sinkTemplates = Template::query()
            ->whereIn('category_id', $sinkCategories)
            ->with([
                'templateProperties',
                'templateProperties.unit',
                'templateProperties.property'
            ])
            ->get();

        $locations = Location::query()->get();

        return [
            $sinkTemplates,
            $locations
        ];
    }
}
