<?php

namespace App\Nova\Actions;

use App\Models\Instance;
use App\Models\Template;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Symfony\Component\Process\Process;

class InstanceProcessing extends Action implements ShouldQueue
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $model) {
            try {
                $this->generateScriptFile($model);
                $this->markAsFinished($model);
            } catch (Exception $e) {
                $this->markAsFailed($model);
            }
        }
    }


    private function getInstanceDependencies(Instance $instance)
    {
        return Instance::with([
            'template',
            'template.templateProperties',
            'template.templateProperties.unit',
            'template.templateProperties.property',
            'template.category',
            'template.category.parent',
        ])->find($instance->id);
    }

    public function getTemplateScript(Instance $instance)
    {
        return Template::with(["scripts"])->find($instance->template_id)->scripts;
    }

    public function generateScriptFile(Instance $__model)
    {
        $now = Carbon::now();
        $path = "scripts/sources/script_file" . $now->timestamp . ".py";

        $modelVariables = "";

        $instance = $this->getInstanceDependencies($__model);
        $sourceScripts = $this->getTemplateScript($instance);

        // Getting all Parent Categories
        $parentCategories = collect([]);
        $parentCategory = $instance->template->category;

        while (!is_null($parentCategory)) {
            $parentCategories->push($parentCategory);
            $parentCategory = $parentCategory->parent;
        }



        $output = View::make('scripts.generate-script', ["model" => $instance, "now" => $now, "sourceScripts" => $sourceScripts])->render();

        Storage::put($path, $output);

        $process = new Process(['python3', Storage::path($path)]);
        $process->run();

        if (!$process->isSuccessful()) throw new Exception($process->getErrorOutput());
        $result = json_decode($process->getOutput(), true);

        $_val = $instance->values;
        $_val["script"] = $result;

        $instance->update([
            "values" => $_val
        ]);
    }
}
