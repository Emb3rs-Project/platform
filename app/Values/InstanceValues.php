<?php

namespace App\Values;

use JessArcher\CastableDataTransferObject\CastableDataTransferObject;

class InstanceValues extends CastableDataTransferObject {
    public string $name;
    public string $fluid;
    public string $capacity;
    public int $sunday_on;
    public int $saturday_on;
    public string $consumer_type;
    public string $daily_periods;
    public string $shutdown_periods;
    public int $supply_temperature;
    public int $target_temperature;
    // public object $characterization;
}
