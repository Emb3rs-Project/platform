<?php

namespace App\Actions\Embers\Objects\Sinks;

use App\Contracts\Embers\Objects\Sinks\ShowsSinks;
use App\EmbersPermissionable;
use App\Models\Instance;
use App\Models\Property;
use App\Models\TemplateProperty;
use App\Models\User;

class ShowSink implements ShowsSinks
{
    use EmbersPermissionable;

    /**
     * Display the given Sink.
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

        $sink = Instance::query()
            ->with(['location', 'template', 'template.category'])
            ->findOrFail($id);

        $templateProperties = TemplateProperty::query()
            ->whereTemplateId($sink->template_id)
            ->with(['property'])
            ->get();

        return [
            $sink,
            $templateProperties,
        ];
    }
}
