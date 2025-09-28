<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        $startTime = $this->faker->dateTimeBetween('+1 week', '+1 month');
        
        return [
            'slug' => $this->faker->unique()->slug(),
            'status' => $this->faker->randomElement(['draft', 'published', 'published']),
            'image' => $this->faker->imageUrl(800, 600, 'event'),
            'max_participants' => $this->faker->numberBetween(5, 20),
            'current_participants' => 0,
            'price' => $this->faker->randomElement([0, 10, 25, 50]),
            'start_time' => $startTime,
            'end_time' => $this->faker->dateTimeBetween($startTime, '+3 hours'),
            'registration_deadline' => $this->faker->dateTimeBetween('+1 day', '+1 week'),
            'views' => $this->faker->numberBetween(0, 1000),
            'published_at' => $this->faker->optional(0.7)->dateTimeThisMonth(),
        ];
    }
}