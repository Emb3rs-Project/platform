<?php

namespace App\Actions\Embers\Objects\Links;

use App\Contracts\Embers\Objects\Links\UpdatesLinks;
use App\EmbersPermissionable;
use App\Models\GeoSegment;
use App\Models\Link;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UpdateLink implements UpdatesLinks
{
    use EmbersPermissionable;

    /**
     * Validate, update and return an existing instance.
     *
     * @param  \App\Models\User  $user
     * @param  int  $id
     * @param  array  $input
     * @return \App\Models\Link
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function update(User $user, int $id, array $input): Link
    {
        $this->authorize($user);

        $link = Link::query()->with(['geoSegments'])->findOrFail($id);

        //$this->validate($input);

        $link = $this->save($link, $input);

        return $link;
    }

    /**
     * Validate the create Sink operation.
     *
     * @param  array  $input
     * @return void
     */
    protected function validate(array $input)
    {
        // Validator::make($input, [
        //     'name' => ['filled', 'string', 'max:255'],
        //     'segments.*.data.to.*' => ['filled', 'numeric'],
        //     'segments.*.data.from.*' => ['filled', 'numeric'],
        //     'description' => ['filled', 'string']
        // ])
        // ->validate();
    }

    /**
     * Save the Sink in the DB.
     *
     * @param  Link $link
     * @param  array    $input
     * @return Link
     */
    protected function save(Link $link, array $input)
    {
        if (!empty($input['name'])) {
            $link->name = $input['name'];
        }

        // if (!empty($input['description'])) {
        //     $link->description = $input['description'];
        // }

        if (!empty($input['segments'])) {
            foreach ($input['segments'] as $data) {
                if (!array_key_exists('id', $data)) {
                    $segment = GeoSegment::create([
                        'data' => $data
                    ]);

                    $link->geoSegments()->attach($segment);
                }
            }
        }

        $link->save();

        return $link;
    }
}
