<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $units = [
            ["name" => "N/A", "symbol" => "", "id" => 9999],
            ["name" => "Energy - kiloWatt", "symbol" => "kW"],
            ["name" => "Temperature - Celcius", "symbol" => "ºC"],
            ["name" => "Pressure - Bar", "symbol" => "Bar"],
            ["name" => "Time - Hour", "symbol" => "h"],
            ["name" => "Time - Day", "symbol" => "day(s)"],
            ["name" => "Time - Month", "symbol" => "month(s)"],
            ["name" => "Percentage", "symbol" => "%"],
            ["name" => "Cost - kiloWatt", "symbol" => "€/kW"],
            ["name" => "Cost - kiloWattHour", "symbol" => "€/kWh"],
            ["name" => "Thermal - J/K", "symbol" => "J/K"],
            ["name" => "Thermal - J/m^2K", "symbol" => "J/m^2K"],
            ["name" => "Thermal - W/m^2K", "symbol" => "W/m^2K"],
            ["name" => "Thermal - W/m^2K^2", "symbol" => "W/m^2K^2"],
            ["name" => "Flow - kg/h", "symbol" => "kg/h"],
            ["name" => "Flow - m^3", "symbol" => "m^3/h"],
            ["name" => "Volume - meter", "symbol" => "m^3"],
            ["name" => "Area - meter", "symbol" => "m^2"],
            ["name" => "Height - meter", "symbol" => "m"],
            ["name" => "Flow - m^3/s", "symbol" => "m^3/s"],
        ];

        foreach ($units as $unit ) {
            Unit::create($unit);
        }
    }
}
