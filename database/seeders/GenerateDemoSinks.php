<?php

namespace Database\Seeders;

use App\Actions\Embers\Objects\Sinks\StoreSink;
use App\Actions\Embers\Objects\Sources\StoreSource;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GenerateDemoSinks extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $email = env('ADMIN_EMAIL', "davidsf@pantherify.com");
        $user = User::where("email", "like", $email)->firstOrFail();
        $sourcesToCreate = [
            [
                "equipment" => [],
                "location" => [
                    "lat" => "39.397222",
                    "lng" => "22.804167"
                ],
                "processes" => [],
                "source" => [
                    "data" => [
                        "name" => "BIOPAR",
                        "consumer_type" => "non-household",
                        'supply_temperature' => 220,
                        'target_temperature' => 120,
                        'fluid' => 'flue_gas',
                        'saturday_on' => 1,
                        'sunday_on' => 1,
                        'shutdown_periods' => '[[274, 300], [335, 365]]',
                        'daily_periods' => '[[0, 24]]',
                        'capacity' => '434',
                    ]
                ],
                "template_id" => 15,
            ],
            [
                "equipment" => [],
                "location" => [
                    "lat" => "39.393056",
                    "lng" => "22.803611"
                ],
                "processes" => [],
                "source" => [
                    "data" => [
                        "name" => "Polysan",
                        "consumer_type" => "non-household",
                        'supply_temperature' => 70,
                        'target_temperature' => 60,
                        'fluid' => 'water',
                        'saturday_on' => 1,
                        'sunday_on' => 1,
                        'shutdown_periods' => '[]',
                        'daily_periods' => '[[0, 24]]',
                        'capacity' => '279',
                    ]
                ],
                "template_id" => 15,
            ],
            [
                "equipment" => [],
                "location" => [
                    "lat" => "39.398611",
                    "lng" => "22.804444"
                ],
                "processes" => [],
                "source" => [
                    "data" => [
                        "name" => "CAO",
                        "consumer_type" => "non-household",
                        'supply_temperature' => 140,
                        'target_temperature' => 120,
                        'fluid' => 'flue_gas',
                        'saturday_on' => 1,
                        'sunday_on' => 1,
                        'shutdown_periods' => '[[182, 240]]',
                        'daily_periods' => '[[6, 21]]',
                        'capacity' => '89',
                    ]
                ],
                "template_id" => 15,
            ],
            [
                "equipment" => [],
                "location" => [
                    "lat" => "39.385556",
                    "lng" => "22.806944"
                ],
                "processes" => [],
                "source" => [
                    "data" => [
                        "name" => "Halibourgia",
                        "consumer_type" => "non-household",
                        'supply_temperature' => 900,
                        'target_temperature' => 500,
                        'fluid' => 'flue_gas',
                        'saturday_on' => 1,
                        'sunday_on' => 1,
                        'shutdown_periods' => '[]',
                        'daily_periods' => '[[0, 6], [22, 24]]',
                        'capacity' => '2436',
                    ]
                ],
                "template_id" => 15,
            ]
        ];

        $sinksToCreate = [
            [
                "location" => [
                    "lat" => "39.394722",
                    "lng" => "22.812222"
                ],
                "sink" => [
                    "data" => [
                        "name" => "SHM_steam",
                        "consumer_type" => "non-household",
                        'supply_temperature' => 73,
                        'target_temperature' => 180,
                        'fluid' => 'steam',
                        'saturday_on' => 0,
                        'sunday_on' => 0,
                        'shutdown_periods' => '[[244, 300]]',
                        'daily_periods' => '[[6, 23]]',
                        'capacity' => '815',
                    ]
                ],
                "template_id" => 14,
            ],
            [
                "location" => [
                    "lat" => "39.394722",
                    "lng" => "22.812222"
                ],
                "sink" => [
                    "data" => [
                        "name" => "SHM_water",
                        "consumer_type" => "non-household",
                        'supply_temperature' => 10,
                        'target_temperature' => 73,
                        'fluid' => 'steam',
                        'saturday_on' => 0,
                        'sunday_on' => 0,
                        'shutdown_periods' => '[[244, 300]]',
                        'daily_periods' => '[[6, 23]]',
                        'capacity' => '79',
                    ]
                ],
                "template_id" => 14,
            ],
            [
                "location" => [
                    "lat" => "39.391389",
                    "lng" => "22.813056"
                ],
                "sink" => [
                    "data" => [
                        "name" => "M-pack_steam",
                        "consumer_type" => "non-household",
                        'supply_temperature' => 73,
                        'target_temperature' => 180,
                        'fluid' => 'steam',
                        'saturday_on' => 0,
                        'sunday_on' => 0,
                        'shutdown_periods' => '[[210, 240]]',
                        'daily_periods' => '[[8, 15]]',
                        'capacity' => '781',
                    ]
                ],
                "template_id" => 14,
            ],
            [
                "location" => [
                    "lat" => "39.391389",
                    "lng" => "22.813056"
                ],
                "sink" => [
                    "data" => [
                        "name" => "M-pack_water",
                        "consumer_type" => "non-household",
                        'supply_temperature' => 10,
                        'target_temperature' => 73,
                        'fluid' => 'water',
                        'saturday_on' => 0,
                        'sunday_on' => 0,
                        'shutdown_periods' => '[[210, 240]]',
                        'daily_periods' => '[[8, 15]]',
                        'capacity' => '76',
                    ]
                ],
                "template_id" => 14,
            ],
            [
                "location" => [
                    "lat" => "39.398056",
                    "lng" => "22.804167"
                ],
                "sink" => [
                    "data" => [
                        "name" => "Aposteirosi_steam",
                        "consumer_type" => "non-household",
                        'supply_temperature' => 73,
                        'target_temperature' => 180,
                        'fluid' => 'steam',
                        'saturday_on' => 1,
                        'sunday_on' => 0,
                        'shutdown_periods' => '[]',
                        'daily_periods' => '[[4, 23]]',
                        'capacity' => '260',
                    ]
                ],
                "template_id" => 14,
            ],
            [
                "location" => [
                    "lat" => "39.398056",
                    "lng" => "22.804167"
                ],
                "sink" => [
                    "data" => [
                        "name" => "Aposteirosi_water",
                        "consumer_type" => "non-household",
                        'supply_temperature' => 10,
                        'target_temperature' => 73,
                        'fluid' => 'water',
                        'saturday_on' => 1,
                        'sunday_on' => 0,
                        'shutdown_periods' => '[]',
                        'daily_periods' => '[[4, 23]]',
                        'capacity' => '25',
                    ]
                ],
                "template_id" => 14,
            ],
            [
                "location" => [
                    "lat" => "39.397222",
                    "lng" => "22.809444"
                ],
                "sink" => [
                    "data" => [
                        "name" => "Biomar_water_1",
                        "consumer_type" => "non-household",
                        'supply_temperature' => 10,
                        'target_temperature' => 80,
                        'fluid' => 'water',
                        'saturday_on' => 1,
                        'sunday_on' => 1,
                        'shutdown_periods' => '[]',
                        'daily_periods' => '[[0, 24]]',
                        'capacity' => '250',
                    ]
                ],
                "template_id" => 14,
            ],
            [
                "location" => [
                    "lat" => "39.397222",
                    "lng" => "22.809444"
                ],
                "sink" => [
                    "data" => [
                        "name" => "Biomar_steam",
                        "consumer_type" => "non-household",
                        'supply_temperature' => 10,
                        'target_temperature' => 175,
                        'fluid' => 'steam',
                        'saturday_on' => 1,
                        'sunday_on' => 1,
                        'shutdown_periods' => '[]',
                        'daily_periods' => '[[0, 24]]',
                        'capacity' => '1250',
                    ]
                ],
                "template_id" => 14,
            ],
            [
                "location" => [
                    "lat" => "39.397222",
                    "lng" => "22.809444"
                ],
                "sink" => [
                    "data" => [
                        "name" => "Biomar_water_2",
                        "consumer_type" => "non-household",
                        'supply_temperature' => 10,
                        'target_temperature' => 90,
                        'fluid' => 'water',
                        'saturday_on' => 1,
                        'sunday_on' => 1,
                        'shutdown_periods' => '[]',
                        'daily_periods' => '[[0, 24]]',
                        'capacity' => '138',
                    ]
                ],
                "template_id" => 14,
            ],
            [
                "location" => [
                    "lat" => "39.396667",
                    "lng" => "22.813889"
                ],
                "sink" => [
                    "data" => [
                        "name" => "Manos_steam",
                        "consumer_type" => "non-household",
                        'supply_temperature' => 64,
                        'target_temperature' => 180,
                        'fluid' => 'water',
                        'saturday_on' => 0,
                        'sunday_on' => 0,
                        'shutdown_periods' => '[]',
                        'daily_periods' => '[[8,15]]',
                        'capacity' => '2777',
                    ]
                ],
                "template_id" => 14,
            ],
            [
                "location" => [
                    "lat" => "39.396667",
                    "lng" => "22.813889"
                ],
                "sink" => [
                    "data" => [
                        "name" => "Manos_water",
                        "consumer_type" => "non-household",
                        'supply_temperature' => 10,
                        'target_temperature' => 64,
                        'fluid' => 'water',
                        'saturday_on' => 0,
                        'sunday_on' => 0,
                        'shutdown_periods' => '[]',
                        'daily_periods' => '[[8,15]]',
                        'capacity' => '231',
                    ]
                ],
                "template_id" => 14,
            ],
            [
                "location" => [
                    "lat" => "39.396111",
                    "lng" => "22.805833"
                ],
                "sink" => [
                    "data" => [
                        "name" => "Elin_steam",
                        "consumer_type" => "non-household",
                        'supply_temperature' => 73,
                        'target_temperature' => 164,
                        'fluid' => 'steam',
                        'saturday_on' => 1,
                        'sunday_on' => 1,
                        'shutdown_periods' => '[]',
                        'daily_periods' => '[[0,24]]',
                        'capacity' => '1539',
                    ]
                ],
                "template_id" => 14,
            ],
            [
                "location" => [
                    "lat" => "39.396111",
                    "lng" => "22.805833"
                ],
                "sink" => [
                    "data" => [
                        "name" => "Elin_water",
                        "consumer_type" => "non-household",
                        'supply_temperature' => 10,
                        'target_temperature' => 73,
                        'fluid' => 'water',
                        'saturday_on' => 1,
                        'sunday_on' => 1,
                        'shutdown_periods' => '[]',
                        'daily_periods' => '[[0,24]]',
                        'capacity' => '153',
                    ]
                ],
                "template_id" => 14,
            ],
        ];

        foreach ($sourcesToCreate as $source) {
            app(StoreSource::class)->store($user, $source);
        }

        foreach($sinksToCreate as $sink) {
            app(StoreSink::class)->store($user, $sink);
        }
    }
}
