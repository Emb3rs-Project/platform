<?php

namespace App\Actions\Embers\News;

use App\Contracts\Embers\News\IndexesNews;
use App\Models\News;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class IndexNews implements IndexesNews
{
    /**
     * Display all the available Projects.
     *
     * @param \App\Models\User
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index(User $user): Collection
    {
        $real_news = News::query()->whereTeamId($user->currentTeam->id)->get();

        $news = $real_news->map(function (News $item) {

            $in_html = substr($item->content, 0, 200) . "...";
            $item->content = closetags($in_html);

            return $item;
        });

        return $news;
    }
}
