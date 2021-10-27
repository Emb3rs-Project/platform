<?php

namespace App\Actions\Embers\Objects\Sinks;

use App\Contracts\Embers\Objects\Sinks\IndexesSinks;
use App\EmbersPermissionable;
use App\Models\Category;
use App\Models\Instance;
use App\Models\Template;
use App\Models\User;

class IndexSink implements IndexesSinks
{
    use EmbersPermissionable;

    /**
     * Display all available Sinks.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function index(User $user)
    {
        // $this->authorize($user);

        // $sinkCategories = Category::query()->whereType('sink')->get('id');

        // $sinkTemplates = Template::query()
        //     ->whereIn('category_id', $sinkCategories)
        //     ->get('id');

        // $teamInstances = $user->currentTeam->instances->pluck('id');

        // $instances = Instance::query()
        //     ->with(['location', 'template', 'template.category'])
        //     ->whereIn('template_id', $sinkTemplates)
        //     ->find($teamInstances);

        // return $instances;
    }
}
