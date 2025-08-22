<?php

namespace Database\Factories;

use App\Models\WorkTimeException;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkTimeExceptionFactory extends Factory
{
    protected $model = WorkTimeException::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'date' => $this->faker->date(),
            'start_time' => $this->faker->time(),
            'end_time' => $this->faker->time(),
            'is_full_day' => false,
            'repeat_annually' => false,
            'reason' => $this->faker->sentence(),
        ];
    }
}
