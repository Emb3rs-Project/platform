<?php

namespace App\Actions\Embers\Objects\Links;

use App\Contracts\Embers\Objects\Links\CreatesLinks;
use App\EmbersPermissionable;
use App\Models\Category;
use App\Models\Location;
use App\Models\Template;
use App\Models\User;

class CreateLink implements CreatesLinks
{
    use EmbersPermissionable;

    /**
     * Display the necessary objects for the creation of a Link.
     *
     * @param  \App\Models\User  $user
     * @return array
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    public function create(User $user): array
    {
        $this->authorize($user);

        $linkCategories = Category::query()->whereType('link')->get('id');

        $linkTemplates = Template::query()
            ->whereIn('category_id', $linkCategories)
            ->with([
                'templateProperties',
                'templateProperties.unit',
                'templateProperties.property'
            ])
            ->orderBy("order")
            ->get();

        $locations = Location::query()->get();

        return [
            $linkTemplates,
            $locations
        ];
    }
}
