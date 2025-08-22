<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceTranslation;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $categories = Category::all(); // Fetch all existing categories

        if ($categories->isEmpty()) {
            $this->command->error('No categories found. Please seed the categories first.');
            return;
        }

        // Predefined services for beauty salon
        $services = [
            'en' => [
                'Manicure', 'Pedicure', 'Haircut', 'Facial Treatment', 'Makeup', 'Eyebrow Shaping', 'Waxing', 'Massage'
            ],
            'ru' => [
                'Маникюр', 'Педикюр', 'Стрижка', 'Уход за лицом', 'Макияж', 'Коррекция бровей', 'Ваксинг', 'Массаж'
            ],
            'et' => [
                'Maniküür', 'Pediküür', 'Juukselõikus', 'Näohooldus', 'Meik', 'Kulmude kujundamine', 'Vahatamine', 'Massaaž'
            ],
            'lv' => [
                'Manikīrs', 'Pedikīrs', 'Matu griešana', 'Sejas kopšana', 'Grims', 'Uzacu veidošana', 'Vaksācija', 'Masāža'
            ],
        ];

        for ($i = 0; $i < 60; $i++) {
            $category = $categories->random(); // Randomly select a category

            $service = Service::create([
                'eventColor' => fake()->hexColor(),
                'price' => fake()->randomFloat(2, 10, 200), // Random price between 10 and 200
                'price_can_change' => fake()->boolean(),
                'duration_minutes_min' => fake()->numberBetween(30, 60),
                'duration_minutes' => fake()->numberBetween(60, 180),
                'status' => 1,
                'category_id' => $category->id, // Assign the category ID
            ]);

            foreach (['en', 'ru', 'et', 'lv'] as $locale) {
                $serviceName = $services[$locale][array_rand($services[$locale])]; // Randomly select a service name
                ServiceTranslation::create([
                    'service_id' => $service->id,
                    'locale' => $locale,
                    'name' => $serviceName,
                    'short_description' => fake()->sentence(),
                    'full_description' => fake()->paragraph(),
                ]);
            }
        }
    }
}
