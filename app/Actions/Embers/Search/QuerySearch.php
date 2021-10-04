<?php

namespace App\Actions\Embers\Search;

use App\Contracts\Embers\Search\QueriesSearch;
use App\Models\Category;
use App\Models\FAQ;
use App\Models\Instance;
use App\Models\Link;
use App\Models\Location;
use App\Models\News;
use App\Models\Project;
use App\Models\Simulation;
use App\Models\Template;
use App\Models\User;

class QuerySearch implements QueriesSearch
{
    /**
     * Search the Searchable models for the query.
     *
     * @param  \App\Models\User  $user
     * @param  string  $query
     * @return \Illuminate\Database\Eloquent\Collection[]
     */
    public function query(User $user, string $query): array
    {
        // Instances (sources && sinks)
        $teamInstances = $user->currentTeam->instances->pluck('id');

        // Sources
        $sourceCategories = Category::query()
            ->whereType('source')
            ->get('id');
        $sourceTemplates = Template::query()
            ->whereIn('category_id', $sourceCategories)
            ->get('id');
        $sources = Instance::search($query)
            ->whereIn('id', $teamInstances->toArray())
            ->whereIn('template_id', $sourceTemplates->toArray())
            ->get();

        // Sinks
        $sinkCategories = Category::query()
            ->whereType('sink')
            ->get('id');
        $sinkTemplates = Template::query()
            ->whereIn('category_id', $sinkCategories)
            ->get('id');
        $sinks = Instance::search($query)
            ->whereIn('id', $teamInstances->toArray())
            ->whereIn('template_id', $sinkTemplates->toArray())
            ->get();

        // Links
        $teamLinks = $user->currentTeam->links->pluck('id');
        $links = Link::search($query)
            ->whereIn('id', $teamLinks->toArray())
            ->get();

        // Locations
        $teamLocations = $user->currentTeam->instances->pluck('location_id');
        $locations = Location::search($query)
            ->whereIn('id', $teamLocations->toArray())
            ->get();

        // Projects
        $teamProjects = $user->currentTeam->projects->pluck('id');
        $projects = Project::search($query)
            ->whereIn('id', $teamProjects->toArray())
            ->get();

        // Simulations
        $simulations = Simulation::search($query)
            ->whereIn('project_id', $teamProjects->toArray())
            ->get();

        // News
        $teamId = $user->currentTeam->id;
        $news = News::search($query)
            ->where('team_id', $teamId)
            ->get();

        // FAQS
        $faqs = FAQ::search($query)
            ->get();

        return [
            'sources' => $sources,
            'sinks' => $sinks,
            'links' => $links,
            'locations' => $locations,
            'projects' => $projects,
            'simulations' => $simulations,
            'news' => $news,
            'faqs' => $faqs,
        ];
    }
}
