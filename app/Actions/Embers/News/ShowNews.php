<?php

namespace App\Actions\Embers\News;

use App\Contracts\Embers\News\ShowsNews;
use App\Models\News;
use App\Models\User;
use Illuminate\Support\Collection;

class ShowNews implements ShowsNews
{
    /**
     * Display all the available Projects.
     *
     * @param \App\Models\User
     * @param  int  $newsId
     * @return \App\Models\News
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function show(User $user, int $newsId): News
    {
        $news = News::query()->whereTeamId($user->currentTeam->id)->findOrFail($newsId);

        return $news;
    }
}
