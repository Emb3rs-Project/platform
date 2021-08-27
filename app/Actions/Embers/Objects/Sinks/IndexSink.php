<?php

namespace App\Actions\Embers\Objects\Sinks;

use App\Contracts\Embers\Objects\Sinks\IndexesSinks;
use App\EmbersPermissionable;
use App\Models\Category;
use App\Models\Instance;
use App\Models\Template;

class IndexSink implements IndexesSinks
{
    use EmbersPermissionable;

    /**
     * Display all the available Sinks.
     *
     * @param  mixed  $user
     * @return mixed
     */
    public function index($user)
    {
        $this->authorize($user);

        $sinkCategories = Category::whereType('sink')->get('id');

        $sinkTemplates = Template::whereIn('category_id', $sinkCategories)
            ->get('id');

        $teamInstances = $user->currentTeam->instances->pluck('id');

        $instances = Instance::whereIn('template_id', $sinkTemplates)
            ->with(['location', 'template', 'template.category'])
            ->find($teamInstances);

        return $instances;
    }
}
