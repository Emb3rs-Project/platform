<?php

namespace App\Actions\Embers\Objects\Sinks;

use App\Contracts\Embers\Objects\Sinks\ShowsSinks;
use App\EmbersPermissionable;
use App\Models\Instance;
use App\Models\Property;
use App\Models\TemplateProperty;

class ShowSink implements ShowsSinks
{
    use EmbersPermissionable;

    /**
     * Find and return an existing Sink.
     *
     * @param  mixed  $user
     * @param  int  $id
     * @return mixed
     */
    public function show($user, int $id)
    {
        $this->authorize($user);

        $sink = Instance::with(['location', 'template', 'template.category'])->findOrFail($id);

        $templateProperties = TemplateProperty::whereTemplateId($sink->template_id)->get('property_id');

        $properties = Property::whereIn('id', $templateProperties)->get('*');

        return [
            $sink,
            $properties
        ];
    }
}
