<?php

namespace App\Actions\Embers\Help;

use App\Contracts\Embers\Help\IndexesHelp;
use App\Models\FAQ;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class IndexHelp implements IndexesHelp
{
    /**
     * Display all the available Faqs.
     *
     * @param  \App\Models\User  $user
     * @param  array  $input
     * @return \Illuminate\Database\Eloquent\Collection<mixed, (\Illuminate\Database\Eloquent\Builder|\App\Models\FAQ)>|array)[]
     */
    public function index(User $user, array $input): array
    {
        $faqs = FAQ::query()->get();

        $validated = Validator::make($input, [
            'faq' => ['sometimes', 'required', 'numeric', Rule::exists(FAQ::class, 'id')]
        ])->validate();

        $faqIdToFocus = Arr::get($validated, 'faq');

        return [$faqs, $faqIdToFocus];
    }
}
