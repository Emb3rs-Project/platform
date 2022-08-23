<?php

namespace App\Jobs;

use App\Contracts\Embers\Integration\CharacterizesInstances;
use App\Models\Instance;
use App\Models\Location;
use App\Models\Template;
use App\Models\User;
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
        $data = (FastExcel::import(Storage::disk('imports')->path($this->filename)))
            ->map(function ($value) {
                return collect($value)->keyBy(function ($value, $key) {
                    return \Str::snake($key);
                });
            });

        $data->each(function ($line) use (&$errors, &$lineCount) {
            $line = collect($line)->toArray();
            info('data', $line);
            $validator = Validator::make($line, [
                'sink_type' => ['required', 'exists:templates,name'],
                'latitude' => ['required', 'numeric', new Coordinates],
                'longitude' => ['required', 'numeric', new Coordinates],
                'name' => ['required'],
                'fluid' => ['required'],
                'supply_temperature' => ['required'],
                'target_temperature' => ['required'],
                'capacity' => ['required'],
                'shutdown_periods' => ['required'],
                'daily_periods' => ['required'],
                'saturday_on' => ['required'],
                'sunday_on' => ['required']
            ]);

            if ($validator->fails()) {
                info('fail', [collect($validator->getMessageBag()->getMessages())->flatten(0)->join(' ')]);
                $errors[] = $this->fillErrors($line,
                    collect($validator->getMessageBag()->getMessages())->flatten(0)->join(' '),
                    $lineCount);
            } else {
                try {
                    $values = [
                        'capacity' => Arr::get($line, 'capacity'),
                        'consumer_type' => Arr::get($line, 'consumer_type'),
                        'daily_periods' => Arr::get($line, 'daily_periods', "[]"),
                        'fluid' => Arr::get($line, 'fluid'),
                        'fluid_cp' => empty(Arr::get($line, 'fluid_cp')) ? null : Arr::get($line, 'fluid_cp'),
                        'name' => Arr::get($line, 'name'),
                        'saturday_on' => Arr::get($line, 'saturday_on') === 'yes' ? 1 : 0,
                        'shutdown_periods' => Arr::get($line, 'shutdown_periods', "[]"),
                        'sunday_on' => Arr::get($line, 'sunday_on') === 'yes' ? 1 : 0,
                        'supply_temperature' => Arr::get($line, 'supply_temperature'),
                        'target_temperature' => Arr::get($line, 'target_temperature'),
                        'flowrate' => empty(Arr::get($line, 'mass_flowrate')) ? null : Arr::get($line, 'mass_flowrate')
                    ];

                    foreach ($values as $key => $value) {
                        if ($value === null || $value === '') {
                            unset($values[$key]);
                        }
                    }

                    $template = Template::whereName(Arr::get($line, 'sink_type'))->first()->toArray();
                    $newInstance = [
                        'name' => Arr::get($line, 'name'),
                        'values' => $values,
                        'template_id' => Arr::get($template, 'id', Template::SIMPLE_SINK),
                    ];

                    if (!is_null(Arr::get($line, 'latitude')) && !is_null(Arr::get($line, 'longitude'))) {
                        // A new location was selected to be used for this Sink
                        $location = Location::create([
                            'name' => Arr::get($line, 'name'),
                            'type' => 'point',
                            'data' => [
                                "center" => [
                                    Arr::get($line, 'latitude'),
                                    Arr::get($line, 'longitude')
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

        } else {
            info(__('File not imported. Check the report file.'),
                [$this->createErrorReport($errors)]);
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
}
