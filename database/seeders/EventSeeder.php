<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\EventTranslation;
use App\Models\EventSeo;
use App\Models\EventSeoTranslation;
use App\Models\EventParticipant;
use App\Models\Language;
use Carbon\Carbon;

class EventSeeder extends Seeder
{public function run(): void
    {
        $languages = Language::all();

        // Таймзона витрины
        $tz = 'Europe/Tallinn';

        // Временные слоты (локальное время Таллинна)
        $timeSlots = [
            [10, 00],
            [12, 00],
            [15, 00],
            [18, 30],
            [19, 00],
        ];

        $events = [];

        // 38 мероприятий по всему октябрю 2025
        for ($i = 1; $i <= 38; $i++) {
            // День месяца от 1 до 31 по кругу
            $day = (($i - 1) % 31) + 1;

            // Берём слот по кругу
            [$h, $m] = $timeSlots[($i - 1) % count($timeSlots)];

            // Локальные дата/время (EET/EEST учитывается Carbon’ом)
            $startTallinn = Carbon::create(2025, 10, $day, $h, $m, 0, $tz);
            $endTallinn   = (clone $startTallinn)->addHours(2);
            $deadlineTallinn = (clone $startTallinn)->subDays(3)->setTime(18, 0, 0);

            // Сохраняем в БД в UTC (истинное время)
            $startUtc    = $startTallinn->clone()->utc();
            $endUtc      = $endTallinn->clone()->utc();
            $deadlineUtc = $deadlineTallinn->clone()->utc();

            // Немного разнообразия по цене/лимиту
            $price = match ($i % 5) {
                0 => 0,
                1 => 9,
                2 => 15,
                3 => 29,
                default => 49,
            };

            $events[] = Event::create([
                'slug'                  => "event-oct-2025-{$i}",
                'status'                => $i <= 20 ? 'published' : 'draft',
                'image'                 => "events/event-{$i}.jpeg",
                'max_participants'      => rand(14, 28),
                'current_participants'  => 0,
                'price'                 => $price,
                'start_time'            => $startUtc,     // UTC
                'end_time'              => $endUtc,       // UTC
                'registration_deadline' => $deadlineUtc,  // UTC
                'views'                 => rand(0, 800),
                'published_at'          => $i <= 20 ? Carbon::now()->subDays(rand(1, 30)) : null,
            ]);
        }

        // Контент/SEO/участники
        foreach ($events as $event) {
            foreach ($languages as $language) {
                EventTranslation::create([
                    'event_id'          => $event->id,
                    'language_id'       => $language->id,
                    'title'             => "Мероприятие {$event->id} на {$language->name}",
                    'short_description' => "Краткое описание мероприятия {$event->id} на {$language->name}",
                    'full_description'  => "Полное описание мероприятия {$event->id} на {$language->name}. Это увлекательное мероприятие с интересной программой.",
                    'location'          => "Кафе эстонского языка, Таллинн",
                    'requirements'      => "Базовые знания эстонского языка",
                    'included'          => "Материалы, кофе-брейк, сертификат",
                ]);
            }

            $eventSeo = EventSeo::create(['event_id' => $event->id]);

            foreach ($languages as $language) {
                EventSeoTranslation::create([
                    'event_seo_id'     => $eventSeo->id,
                    'language_id'      => $language->id,
                    'meta_title'       => "Мероприятие {$event->id} - Кафе эстонского языка",
                    'meta_description' => "Присоединяйтесь к нашему мероприятию по изучению эстонского языка",
                    'meta_keywords'    => "эстонский язык, мероприятие, обучение, Таллинн",
                ]);
            }

            for ($j = 1; $j <= 3; $j++) {
                EventParticipant::create([
                    'event_id'            => $event->id,
                    'first_name'          => "Участник{$j}",
                    'last_name'           => "Тестовый",
                    'email'               => "participant{$j}.event{$event->id}@example.com",
                    'phone'               => "+372 5555555{$j}",
                    'status'              => $j === 1 ? 'confirmed' : 'pending',
                    'participants_count'  => 1,
                    'notes'               => "Тестовый участник {$j}",
                ]);
            }
        }
    }
}