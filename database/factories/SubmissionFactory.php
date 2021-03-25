<?php

namespace Database\Factories;

use App\Models\Submission;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubmissionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Submission::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'atmosphere' => $this->faker->numberBetween(-2, 2),
            'direction' => $this->faker->numberBetween(-1, 1),
            'abandoned' => $this->faker->boolean(),
            'comment' => $this->faker->text($this->faker->numberBetween(5, 200)),
        ];
    }
}
