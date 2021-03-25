<?php

namespace Database\Factories;

use App\Models\Cause;
use Illuminate\Database\Eloquent\Factories\Factory;

class CauseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Cause::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'text' => $this->faker->sentence(3),
        ];
    }
}
