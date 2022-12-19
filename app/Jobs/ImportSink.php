<?php

namespace App\Jobs;

use App\Contracts\Embers\Integration\CharacterizesInstances;
use App\Models\Instance;
use App\Models\Location;
use App\Models\Template;
use App\Models\User;
use App\Notifications\Embers\ImportNotification;
use App\Notifications\Embers\SimulationNotification;
use App\Rules\Coordinates;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Rap2hpoutre\FastExcel\Facades\FastExcel;
use Storage;

class ImportSink implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $filename;
    protected User $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $filename, User $user)
    {
        $this->filename = $filename;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $errors = [];
        $lineCount = 1;
        $data = (FastExcel::import(Storage::disk('imports')->path($this->filename)));

        list($rules, $props, $bindProps) = $this->getPropsAndRules();
        $rules = array_merge([
            'template' => ['required', 'exists:templates,name'],
            'latitude' => ['required', 'numeric', new Coordinates],
            'longitude' => ['required', 'numeric', new Coordinates]
        ], $rules);
        $data->each(function ($line) use (&$errors, &$lineCount, $rules, $props, $bindProps) {
            $bindLine = [];
            collect($line)->each(function ($value, $key) use (&$bindLine, $bindProps) {
                if (array_key_exists($key, $bindProps)) {
                    $bindLine[$bindProps[$key]['att']] = $value;
                } else {
                    $bindLine[$key] = $value;
                }

            });
            $line = $bindLine;

            $validator = Validator::make($line, $rules);

            if ($validator->fails()) {
                info('fail', [collect($validator->getMessageBag()->getMessages())->flatten(0)->join(' ')]);
                $errors[] = $this->fillErrors($line,
                    collect($validator->getMessageBag()->getMessages())->flatten(0)->join(' '),
                    $lineCount);
            } else {
                try {
                    $values = [];
                    foreach ($props as $prop) {
                        if (in_array($bindProps[$prop]['att'], ['shutdown_periods', 'daily_periods'])) {
                            $values[$bindProps[$prop]['att']] = Arr::get($line, $bindProps[$prop]['att'], "[]");
                        } else if (in_array($bindProps[$prop]['att'], ['sunday_on', 'saturday_on'])) {
                            $values[$bindProps[$prop]['att']] = Arr::get($line, $bindProps[$prop]['att'], $bindProps[$prop]['default_value']) === 'yes' ? 1 : 0;
                        } else {
                            $values[$bindProps[$prop]['att']] = empty(Arr::get($line, $bindProps[$prop]['att'], $bindProps[$prop]['default_value'])) ? null : Arr::get($line, $bindProps[$prop]['att'], $bindProps[$prop]['default_value']);
                        }
                    }

                    foreach ($values as $key => $value) {
                        if ($value === null || $value === '') {
                            unset($values[$key]);
                        }
                    }
                    $template = Template::whereName(Arr::get($line, 'template'))->first()->toArray();
                    $newInstance = [
                        'name' => Arr::get($line, 'name'),
                        'values' => $values,
                        'template_id' => Arr::get($template, 'id', Template::SIMPLE_SINK),
                    ];

                    if (!is_null(Arr::get($line, 'latitude')) && !is_null(Arr::get($line, 'longitude'))) {
                        // A new location was selected to be used for this Sink
                        $lat = Arr::get($line, 'latitude');
                        $lng = Arr::get($line, 'longitude');
                        if (str_contains($lat, ',')) {
                            $lat = str_replace(',', '.', $lat);
                        }
                        if (str_contains($lng, ',')) {
                            $lng = str_replace(',', '.', $lng);
                        }
                        $location = Location::create([
                            'name' => Arr::get($line, 'name'),
                            'type' => 'point',
                            'data' => [
                                "center" => [
                                    $lat,
                                    $lng
                                ]
                            ]
                        ]);

                        Arr::set($newInstance, 'location_id', $location->id);
                    }

                    $instance = Instance::create($newInstance);
                    $instance->teams()->attach($this->user->currentTeam);

                    app(CharacterizesInstances::class)->characterize($instance);
                } catch (Exception $e) {
                    info('Error Import Sink: create sink: ', [$line]);
                    info('Error: ' . $e->getMessage());
                    $errors[] = $this->fillErrors($line, 'Server Error', $lineCount);
                }

            }
            $lineCount ++;
        });

        if (!count($errors)) {
            info(__('File imported successfully'));
            $this->user->notify(new ImportNotification($this->user, $this->user->currentTeam,
                [],
                'The Excel file was imported successfully. '
            ));

        } else {
            $filename = $this->createErrorReport($errors);
            info(__('File not imported. Check the report file.'),
                [$filename]);
            $this->user->notify(new ImportNotification($this->user, $this->user->currentTeam,
                [],
                'The Excel file was imported but some line(s) got failed. Download the failed report:
                <a style="text-decoration-line: underline;text-decoration-thickness: 2px;" target="_blank" href="' . config('app.url') . '/storage/failures/' . $filename . '">' . $filename . '</a> '
            ));
        }
        Storage::disk('imports')->delete($this->filename);
    }

    /**
     * Creates a path for the error report.
     *
     * @return string
     */
    private function getReportFileName(): string
    {
        return class_basename($this) . '_import_failures_' . now()->format('YmdHis') . '.xlsx';
    }

    /**
     * @param $fileHeaders
     * @return bool
     */
    private function validateHeaders($fileHeaders): bool
    {
        return !count(array_diff($this->importHeaders(), $fileHeaders));
    }

    /**
     * @param $line
     * @param $message
     * @param $index
     * @return array
     */
    private function fillErrors($line, $message, $index): array
    {
        return array_merge(['original row' => $index], $line, ['error' => $message]);
    }

    /**
     * @param array $errors
     * @return string
     */
    private function createErrorReport(array $errors): string
    {
        $reportFileName = $this->getReportFileName();
        (new \Rap2hpoutre\FastExcel\FastExcel(collect($errors)))->export(Storage::disk('failures')->path($reportFileName));
        return $reportFileName;
    }

    /**
     * @return string[]
     */
    private function importHeaders(): array
    {
        return [];
    }

    /**
     * @return array
     */
    protected function getPropsAndRules(): array
    {
        $templates = Template::with('templateProperties', 'templateProperties.property')->get();
        $rules = [];
        $allProps = [];
        $propsBind = [];
        $templates->each(function ($item) use (&$rules, &$allProps, &$propsBind) {
            $item->templateProperties->each(function ($tempProp) use (&$allProps, &$rules, $item, &$propsBind) {
                if ($tempProp->required) {
                    $rules[$tempProp->property['symbolic_name']][] = 'required_if:template,' . $item->name;
                }
                $allProps[] = $tempProp->property['name'];
                $propsBind[$tempProp->property['name']] = ['att' =>$tempProp->property['symbolic_name'], 'default_value' => $tempProp->default_value ?? null];
            });
        });

        return [$rules, array_unique($allProps), $propsBind];
    }
}
