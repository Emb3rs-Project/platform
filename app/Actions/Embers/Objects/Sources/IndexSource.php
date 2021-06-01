<?php

namespace App\Actions\Embers\Objects\Sources;

use App\Contracts\Embers\Objects\Sources\IndexesSources;
use App\Models\Category;
use App\Models\Instance;
use App\Models\Template;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class IndexSource implements IndexesSources
{
    /**
     * Display all the available Sources.
     *
     * @param  mixed  $user
     * @return mixed
     */
    public function index($user)
    {
        Gate::authorize('viewAny', Instance::class);

        $sourceCategories = Category::whereType('source')->get()->pluck('id');

        $sourceTemplates = Template::whereIn('category_id', $sourceCategories)
            ->with([
                'templateProperties',
                'templateProperties.unit',
                'templateProperties.property'
            ])
            ->get()
            ->pluck('id');

        $teamInstances = Auth::user()->currentTeam->instances->pluck('id');

        $instances = Instance::whereIn('template_id', $sourceTemplates)
            ->whereIn('id', $teamInstances)
            ->with([ 'location', 'template', 'template.category'])
            ->get();

        return $instances;
    }
}
