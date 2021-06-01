<?php

namespace App\Actions\Embers\Objects\Sources;

use App\Contracts\Embers\Objects\Sources\IndexesSources;
use App\Models\Category;
use App\Models\Instance;
use App\Models\Template;

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
        // abort_unless($user->hasTeamPermission($user->currentTeam, 'index-source'), 401);

        $sourceCategories = Category::whereType('source')->get()->pluck('id');

        $sourceTemplates = Template::whereIn('category_id', $sourceCategories)
            ->with([
                'templateProperties',
                'templateProperties.unit',
                'templateProperties.property'
            ])
            ->get()
            ->pluck('id');

        $teamInstances = $user->currentTeam->instances->pluck('id');

        $instances = Instance::whereIn('template_id', $sourceTemplates)
            ->whereIn('id', $teamInstances)
            ->with([ 'location', 'template', 'template.category'])
            ->get();

        return $instances;
    }
}
