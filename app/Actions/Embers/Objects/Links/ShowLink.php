<?php

namespace App\Actions\Embers\Objects\Links;

use App\Contracts\Embers\Objects\Links\ShowsLinks;
use App\EmbersPermissionable;
use App\Models\Category;
use App\Models\Link;
use App\Models\Template;
use App\Models\TemplateProperty;
use App\Models\User;
use Illuminate\Support\Collection;

class ShowLink implements ShowsLinks
{
    use EmbersPermissionable;

    /**
     * Find and return an existing Sink.
     *
     * @param  \App\Models\User  $user
     * @param  int  $id
     * @return \Illuminate\Support\Collection
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function show(User $user, int $id): array
    {
        $this->authorize($user);

        $teamLinks = $user->currentTeam->links->pluck('id');

        $link = Link::query()
            ->with(['geoSegments'])
            ->whereIn('id', $teamLinks)
            ->findOrFail($id);
        
        $templateProperties = [];
        foreach($link->geoSegments as $geoSegment) {
            array_push($templateProperties, 
                array_key_exists('template_id', $geoSegment['data']) 
                    ? TemplateProperty::query()
                        ->whereTemplateId($geoSegment['data']['template_id'])
                        ->with(['property'])
                        ->get()
                    : []
            );
        }

        return [
            $link,
            $templateProperties,
        ];
    }
}
