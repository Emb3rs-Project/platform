<?php

namespace App\Actions\Embers\Objects\Links;

use App\Contracts\Embers\Objects\Links\EditsLinks;
use App\EmbersPermissionable;
use App\Models\Category;
use App\Models\Link;
use App\Models\Location;
use App\Models\Template;
use App\Models\TemplateProperty;
use App\Models\User;

class EditLink implements EditsLinks
{
    use EmbersPermissionable;

    /**
     * Display the necessary objects for updating a given Link.
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

        $link = Link::query()->with(['geoSegments'])->findOrFail($id);

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

        return [
            $linkTemplates,
            $link
        ];
    }
}
