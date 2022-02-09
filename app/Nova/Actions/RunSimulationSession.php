<?php

namespace App\Nova\Actions;

use App\Models\SimulationSession;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redis;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class RunSimulationSession extends Action
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
            if ($model instanceof SimulationSession) {
                $data = $this->metadata;
                $data['simulation_uuid'] = $model->simulation_uuid;

                Redis::publish('simulation_started', json_encode($data));
            }
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [];
    }


    private $metadata = [
        'simulation_uuid' => '17cf4845-f204-41f5-864f-d2ec751a7fea',
        'simulation_metadata' => [
            'type' => 'simulation',
            'start' => '485df2d8-bdd5-4158-ba6f-743199a522d0',
            '485df2d8-bdd5-4158-ba6f-743199a522d0' => [
                'module' => [
                    'name' => 'cf-module',
                    'publish_channel' => 'cf_queue',
                    'job_channel' => 'cf_jobs',
                ],
                'function' => 'cf:sink:convert_sinks',
                'next' => '485df2d8-bdd5-4158-ba6f-743199a522d0',
            ],
            'dbd39c07-da5a-4ff4-88cd-e10d06ddcb21' => [
                'module' => [
                    'name' => 'cf-module',
                    'publish_channel' => 'cf_queue',
                    'job_channel' => 'cf_jobs',
                ],
                'function' => 'cf:source_detailed:convert_sources',
            ],
        ],
        'initial_data' => [
            'group_of_sinks' => [
                0 => [
                    'id' => 1,
                    'consumer_type' => 'non-household',
                    'location' => [
                        0 => 10,
                        1 => 10,
                    ],
                    'streams' => [
                        0 => [
                            'id' => 2,
                            'object_type' => 'stream',
                            'stream_type' => 'inflow',
                            'fluid' => 'water',
                            'capacity' => 263,
                            'supply_temperature' => 10,
                            'target_temperature' => 80,
                            'schedule' => [
                                0 => 1,
                                1 => 1,
                                2 => 1,
                            ],
                            'hourly_generation' => [
                                0 => 1000,
                                1 => 1000,
                                2 => 1000,
                            ],
                        ],
                    ],
                ],
                1 => [
                    'id' => 56,
                    'consumer_type' => 'household',
                    'location' => [
                        0 => 11,
                        1 => 11,
                    ],
                    'streams' => [
                        0 => [
                            'id' => 2,
                            'object_type' => 'stream',
                            'stream_type' => 'inflow',
                            'fluid' => 'water',
                            'capacity' => 263,
                            'supply_temperature' => 10,
                            'target_temperature' => 80,
                            'schedule' => [
                                0 => 1,
                                1 => 1,
                                2 => 1,
                            ],
                            'hourly_generation' => [
                                0 => 1000,
                                1 => 1000,
                                2 => 1000,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ];
}
