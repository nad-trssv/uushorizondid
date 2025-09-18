<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\EventTranslation;
use App\Models\Language;
use App\Models\User;
use Faker\Factory as Faker;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $languages = Language::all();
        $fakerDefault = Faker::create();

        // Custom event — кино в Õismäe
        $event = Event::create([
            'slug' => 'open-air-cinema-oismae',
            'type' => 'offline',

            'all_day' => false,
            'location' => 'Õismäe',
            'starts_at' => '2025-08-10 20:00:00',
            'ends_at' => '2025-08-10 22:00:00',
            'created_by' => User::inRandomOrder()->first()->id,
        ]);

        $translations = [
            'ru' => [
                'title' => 'Кино под открытым небом в Õismäe',
                'description' => 'В августе мы вместе отправились в Õismäe на кино под открытым небом! '
                    . 'Фильм "Fränk" показала администрация района Хааберсти, и это был настоящий подарок. '
                    . 'Вечер у пруда, хорошее кино и отличная компания — что может быть лучше? '
                    . 'Спасибо всем, кто был с нами!',
            ],
            'et' => [
                'title' => 'Vabaõhukino Õismäel',
                'description' => 'Augustis käisime koos Õismäel vabaõhukinos! '
                    . 'Filmi "Fränk" linastuse korraldas Haabersti linnaosa valitsus. '
                    . 'Õhtu tiigi ääres, hea film ja veel parem seltskond — lihtsalt imeline! '
                    . 'Aitäh kõigile, kes meiega ühinesid!',
            ],
            'en' => [
                'title' => 'Open-Air Cinema in Õismäe',
                'description' => 'In August, we went together to an open-air cinema in Õismäe! '
                    . 'The film "Fränk" was screened thanks to the Haabersti district administration. '
                    . 'A magical evening by the pond, with a great movie and even better company. '
                    . 'Thank you to everyone who joined us!',
            ],
        ];

        foreach ($languages as $lang) {
            $data = $translations[$lang->code] ?? null;
            if ($data) {
                EventTranslation::create([
                    'event_id' => $event->id,
                    'language_id' => $lang->id,
                    'title' => $data['title'],
                    'description' => $data['description'],
                ]);
            }
        }

        $event->gallery()->createMany([
            ['image' => 'uploads/events/oismae_1.jpg', 'alt' => 'Фильм "Fränk" у пруда'],
            ['image' => 'uploads/events/oismae_2.jpg', 'alt' => 'Зрители на мероприятии'],
        ]);

        // Generate 5 more events
        for ($i = 2; $i <= 6; $i++) {
            $slug = "event-$i";
            $type = ['online', 'offline', 'hybrid'][rand(0, 2)];
            $location = $fakerDefault->city();
            $startsAt = now()->addDays(rand(5, 30));
            $endsAt = $startsAt->copy()->addHours(2);

            $event = Event::create([
                'slug' => $slug,
                'type' => $type,
                'location' => $location,
                'starts_at' => $startsAt,
                'ends_at' => $endsAt,
                'created_by' => User::inRandomOrder()->first()->id,
            ]);

            foreach ($languages as $lang) {
                $faker = Faker::create($lang->code);

                EventTranslation::create([
                    'event_id' => $event->id,
                    'language_id' => $lang->id,
                    'title' => $faker->sentence(6),
                    'description' => $faker->paragraph(3),
                ]);
            }

            $event->gallery()->createMany([
                ['image' => "uploads/events/{$slug}_1.jpg", 'alt' => "Изображение 1 для {$slug}"],
                ['image' => "uploads/events/{$slug}_2.jpg", 'alt' => "Изображение 2 для {$slug}"],
            ]);
        }
    }
}
