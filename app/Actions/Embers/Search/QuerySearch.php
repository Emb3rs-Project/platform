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
use Illuminate\Support\Collection;

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
        $teamInstances = $user->currentTeam->instances;

        $teamInstanceIds = $teamInstances->pluck('id');
        $teamInstanceLocationIds = $teamInstances->pluck('location_id');

        $teamLinkIds = $user->currentTeam->links->pluck('id');
        $teamProjectIds = $user->currentTeam->projects->pluck('id');

        $teamId = $user->currentTeam->id;

        $sources = $this->querySearchSources($teamInstanceIds, $query);
        $sinks = $this->querySearchSinks($teamInstanceIds, $query);
        $links = $this->querySearchLinks($teamLinkIds, $query);
        $locations = $this->querySearchLocations($teamInstanceLocationIds, $query);
        $projects = $this->querySearchProjects($teamProjectIds, $query);
        $simulations = $this->querySearchSimulations($teamProjectIds, $query);
        $news = $this->querySearchNews($teamId, $query);
        $faqs = $this->querySearchFaqs($query);

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

    /**
     * @param  \Illuminate\Database\Eloquent\Collection  $teamInstances
     * @param  string  $query
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function querySearchSources(Collection $teamInstanceIds, string $query): Collection
    {
        $sourceCategories = Category::query()
            ->whereType('source')
            ->get('id');
        $sourceTemplates = Template::query()
            ->whereIn('category_id', $sourceCategories)
            ->get('id');
        $sources = Instance::search($query)
            ->whereIn('id', $teamInstanceIds->toArray())
            ->whereIn('template_id', $sourceTemplates->toArray())
            ->get();

        return $sources;
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Collection  $teamInstances
     * @param  string  $query
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function querySearchSinks(Collection $teamInstanceIds, string $query): Collection
    {
        $sinkCategories = Category::query()
            ->whereType('sink')
            ->get('id');
        $sinkTemplates = Template::query()
            ->whereIn('category_id', $sinkCategories)
            ->get('id');
        $sinks = Instance::search($query)
            ->whereIn('id', $teamInstanceIds->toArray())
            ->whereIn('template_id', $sinkTemplates->toArray())
            ->get();

        return $sinks;
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Collection  $teamLinks
     * @param  string  $query
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function querySearchLinks(Collection $teamLinkIds, string $query): Collection
    {
        $links = Link::search($query)
            ->whereIn('id', $teamLinkIds->toArray())
            ->get();

        return $links;
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Collection  $teamInstanceLocationIds
     * @param  string  $query
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function querySearchLocations(Collection $teamInstanceLocationIds, string $query): Collection
    {
        $locations = Location::search($query)
            ->whereIn('id', $teamInstanceLocationIds->toArray())
            ->get();

        return $locations;
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Collection  $teamProjectIds
     * @param  string  $query
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function querySearchProjects(Collection $teamProjectIds, string $query): Collection
    {
        $projects = Project::search($query)
            ->whereIn('id', $teamProjectIds->toArray())
            ->get();

        return $projects;
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Collection  $teamProjectIds
     * @param  string  $query
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function querySearchSimulations(Collection $teamProjectIds, string $query): Collection
    {
        $simulations = Simulation::search($query)
            ->whereIn('project_id', $teamProjectIds->toArray())
            ->get();

        return $simulations;
    }

    /**
     * @param  int  $teamId
     * @param  string  $query
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function querySearchNews(int $teamId, string $query): Collection
    {
        $news = News::search($query)
            ->where('team_id', $teamId)
            ->get();

        return $news;
    }

    /**
     * @param  string  $query
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function querySearchFaqs(string $query): Collection
    {
        $faqs = FAQ::search($query)
            ->get();

        return $faqs;
    }
}
