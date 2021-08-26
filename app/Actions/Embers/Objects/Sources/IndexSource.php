<?php

namespace App\Actions\Embers\Objects\Sources;

use App\Contracts\Embers\Objects\Sources\IndexesSources;
use App\EmbersPermissionable;
use App\Models\Category;
use App\Models\Instance;
use App\Models\Template;

class IndexSource implements IndexesSources
{
    use EmbersPermissionable;

    /**
     * Display all the available Sources.
     *
     * @param  mixed  $user
     * @return mixed
     */
    public function index($user)
    {
        $this->authorize($user);

        $sourceCategories = Category::whereType('source')->get('id');

        $sourceTemplates = Template::whereIn('category_id', $sourceCategories)
            ->get('id');

        $teamInstances = $user->currentTeam->instances->pluck('id');

        $instances = Instance::whereIn('template_id', $sourceTemplates)
            ->with(['location', 'template', 'template.category'])
            ->find($teamInstances);

        return $instances;
    }
}
