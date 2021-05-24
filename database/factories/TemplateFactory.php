<?php

namespace Database\Factories;

use App\Models\Template;
use Illuminate\Database\Eloquent\Factories\Factory;

class TemplateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Template::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "name" => $this->faker->colorName . " Template",
            "values" => []
        ];
    }


    public function isProcess()
    {
        return $this->state([
            "name" => fn () => $this->faker->colorName . " Process [Seed]",
        ]);
    }

    public function isSink()
    {
        return $this->state([
            "name" => $this->faker->colorName . " Sink [Seed]"
        ]);
    }

    public function isSource()
    {
        return $this->state([
            "name" => $this->faker->colorName . " Source [Seed]"
        ]);
    }
}
