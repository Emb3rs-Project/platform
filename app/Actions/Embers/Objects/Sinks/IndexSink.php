<?php

namespace App\Actions\Embers\Objects\Sinks;

use App\Contracts\Embers\Objects\Sinks\IndexesSinks;
use App\Models\Category;
use App\Models\Instance;
use App\Models\Template;
use Illuminate\Support\Facades\Gate;

class IndexSink implements IndexesSinks
{
    /**
     * Display all the available Sinks.
     *
     * @param mixed  $user
     * @return mixed
     */
    public function index(mixed $user)
    {
        Gate::authorize('viewAny', Instance::class);

        $sinkCategories = Category::whereType('sink')
            ->get()
            ->pluck('id');

        $templates = Template::whereIn('category_id', $sinkCategories)
            ->get()
            ->pluck('id');

        $teamInstances = $user->currentTeam->instances->pluck('id');

        $sinks = Instance::whereIn('template_id', $templates)
            ->whereIn('id', $teamInstances)
            ->with(['template', 'template.category'])
            ->get();

        return $sinks;
    }
}
