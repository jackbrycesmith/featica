<?php

namespace Featica\Tests\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Featica\Tests\Factories\Concerns\HasFeatureFlagStates;
use Featica\Tests\Models\Team;

class TeamFactory extends Factory
{
    use HasFeatureFlagStates;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Team::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
             'name' => $this->faker->name,
        ];
    }
}
