<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\Heading;

class ImportSource extends Action
{
    use InteractsWithQueue, Queueable;


    public function name()
    {
        return __('Import Sources');
    }

    /**
     * Perform the action on the given models.
     *
     * @param \Laravel\Nova\Fields\ActionFields $fields
     * @param \Illuminate\Support\Collection $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $file = $fields['excel_file'];

        $filePath = $file->store('imports');
        list($path, $filename) = explode('/', $filePath);
        \App\Jobs\ImportSource::dispatch($filename, request()->user());

        return Action::message(__('The file is being processed in the background. You will receive a notification when finished.'));
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            File::make('Excel file')->disk('imports'),
            Heading::make('<p>' .
                __('Download an example file:') .
                ' <a href="/samples/source_import_sample.xlsx" class="font-bold text-primary">' .
                __('click here') .
                '</a></p>')->asHtml()
        ];
    }
}
