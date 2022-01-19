<?php

namespace Database\Seeders;

use App\Models\Property;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // text, datetime, select, week_schedule
        $properties = [
            ["name" => "Name", "symbolic_name" => "name", "dataType" => "String", "inputType" => "text", "data" => []],
            ["name" => "Working Days", "symbolic_name" => "working_days", "dataType" => "String", "inputType" => "text", "data" => []],
            ["name" => "Start Time", "symbolic_name" => "start_time", "dataType" => "String", "inputType" => "text", "data" => []],
            ["name" => "Working Hours", "symbolic_name" => "working_hours", "dataType" => "String", "inputType" => "text", "data" => []],
            ["name" => "Supply Capacity", "symbolic_name" => "supply_capacity", "dataType" => "String", "inputType" => "text", "data" => []],

            // DATETIME
            ["name" => "Holiday Start Date", "symbolic_name" => "holiday_start_date", "dataType" => "String", "inputType" => "text", "data" => []],
            ["name" => "Holiday End Date", "symbolic_name" => "holiday_end_date", "dataType" => "String", "inputType" => "text", "data" => []],

            // SELECTS
            [
                "name" => "Outflow Type",
                "symbolic_name" => "typer",
                "dataType" => "String",
                "inputType" => "select",
                "data" => [
                    "options" => [
                        ["value" => "Type One", "key" => "typeone"],
                        ["value" => "Type Two", "key" => "typetwo"],
                    ]
                ]
            ],
            [
                "name" => "Industry Type",
                "symbolic_name" => "type",
                "dataType" => "String",
                "inputType" => "select",
                "data" => [
                    "options" => [
                        ["value" => "Type One", "key" => "typeone"],
                        ["value" => "Type Two", "key" => "typetwo"],
                    ]
                ]
            ],
            [
                "name" => "Sunday Off?",
                "symbolic_name" => "sunday_off",
                "dataType" => "Number",
                "inputType" => "select",
                "data" => [
                    "options" => [
                        ["value" => "Yes", "key" => 1],
                        ["value" => "No", "key" => 0],
                    ]
                ]
            ],
            [
                "name" => "Saturday Off?",
                "symbolic_name" => "saturday_off",
                "dataType" => "Number",
                "inputType" => "select",
                "data" => [
                    "options" => [
                        ["value" => "Yes", "key" => 1],
                        ["value" => "No", "key" => 0],
                    ]
                ]
            ],


            // OUTPUTS
            ["name" => "Excess Heat Fluid", "symbolic_name" => "excess_heat_fluid", "dataType" => "String", "inputType" => "text", "data" => []],
            ["name" => "Excess Heat Temperature", "symbolic_name" => "excess_heat_temperature", "dataType" => "String", "inputType" => "text", "data" => []],
            ["name" => "Excess Heat Flow Rate", "symbolic_name" => "excess_heat_flowrate", "dataType" => "String", "inputType" => "text", "data" => []],
            ["name" => "HC Fluid Type", "symbolic_name" => "hc_fluid_type", "dataType" => "String", "inputType" => "text", "data" => []],
            ["name" => "HC Supply Temperature", "symbolic_name" => "hc_supply_temperature", "dataType" => "String", "inputType" => "text", "data" => []],
            ["name" => "HC Supply Capacity", "symbolic_name" => "hc_supply_capacity", "dataType" => "String", "inputType" => "text", "data" => []],
            ["name" => "HC Flow Rate", "symbolic_name" => "hc_flowrate", "dataType" => "String", "inputType" => "text", "data" => []],
            ["name" => "OM Variable", "symbolic_name" => "OM_Variable", "dataType" => "String", "inputType" => "text", "data" => []],
        ];



        foreach ($properties as $property) {
            Property::create($property);
        }
    }
}
