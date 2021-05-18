<?php

namespace App\Actions\Embers\Objects\Sources;

use App\Contracts\Embers\Objects\Sources\IndexesSources;
use App\Models\Category;
use App\Models\Instance;
use App\Models\Template;
use Illuminate\Support\Facades\Gate;

class IndexSource implements IndexesSources
{
    /**
     * Display all the available Sources.
     *
     * @param mixed  $user
     * @return mixed
     */
    public function index(mixed $user)
    {
        Gate::authorize('viewAny', Instance::class);

        $sourceCategories = Category::whereType('source')->get()->pluck('id');

        // $equipmentCategories = Category::whereType('equipment')->get()->pluck('id');

        // $processCategories = Category::whereType('process')->get()->pluck('id');

        $sourceTemplates = Template::whereIn('category_id', $sourceCategories)
            ->with([
                'templateProperties',
                'templateProperties.unit',
                'templateProperties.property'
            ])
            ->get();

        // $equipmentTemplates = Template::whereIn('category_id', $equipmentCategories)
        //     ->with([
        //         'templateProperties',
        //         'templateProperties.unit',
        //         'templateProperties.property'
        //     ])
        //     ->get();

        // $processTemplates = Template::whereIn('category_id', $processCategories)
        //     ->with([
        //         'templateProperties',
        //         'templateProperties.unit',
        //         'templateProperties.property'
        //     ])
        //     ->get();


        $teamInstances = $user->currentTeam->instances->pluck('id');

        $instances = Instance::whereIn('template_id', $sourceTemplates)
            ->whereIn('id', $teamInstances)
            ->with(['template', 'template.category', 'location.geoObject'])
            ->get();

        return response()->json([
            'sources' => $instances
        ]);

        $output = $instances->map(function ($item) {
            if (isset($item->location)) {
                $item['data'] = $item->location->geoObject;
            }

            return $item;
        });

        return $output;
    }

    public function redirectTo()
    {
    }
}
