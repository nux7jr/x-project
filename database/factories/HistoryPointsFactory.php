<?php

namespace Database\Factories;

use App\Models\HistoryPoints;
use Illuminate\Database\Eloquent\Factories\Factory;

class HistoryPointsFactory extends Factory
{
    protected $model = HistoryPoints::class;

    public function definition(): array
    {
        return [
            'user_id' => null, 
            'score' => $this->faker->numberBetween(1, 10000),
            'created_at' => $this->faker->dateTimeBetween('-1 months', 'now'),
        ];
    }
}

