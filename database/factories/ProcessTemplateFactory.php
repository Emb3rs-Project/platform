<?php

namespace Database\Factories;

use App\Models\Model;
use App\Models\Template;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProcessTemplateFactory extends Factory
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
            "name" => $this->faker->colorName . " Process",
            "values" => []
        ];
    }
}
