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
{
    public function run(): void
    {
        $languages = Language::all();
        
        // Создаем 5 тестовых мероприятий вручную
        $events = [];
        for ($i = 1; $i <= 5; $i++) {
            $startTime = Carbon::now()->addWeeks($i);
            
            $events[] = Event::create([
                'slug' => "event-{$i}",
                'status' => $i <= 3 ? 'published' : 'draft',
                'image' => "events/event-{$i}.jpg",
                'max_participants' => rand(10, 25),
                'current_participants' => 0,
                'price' => rand(0, 50),
                'start_time' => $startTime,
                'end_time' => $startTime->copy()->addHours(2),
                'registration_deadline' => $startTime->copy()->subDays(3),
                'views' => rand(0, 500),
                'published_at' => $i <= 3 ? Carbon::now()->subDays(rand(1, 30)) : null,
            ]);
        }

        foreach ($events as $event) {
            // Создаем переводы для каждого языка
            foreach ($languages as $language) {
                EventTranslation::create([
                    'event_id' => $event->id,
                    'language_id' => $language->id,
                    'title' => "Мероприятие {$event->id} на {$language->name}",
                    'short_description' => "Краткое описание мероприятия {$event->id} на {$language->name}",
                    'full_description' => "Полное описание мероприятия {$event->id} на {$language->name}. Это увлекательное мероприятие с интересной программой.",
                    'location' => "Кафе эстонского языка, Таллинн",
                    'requirements' => "Базовые знания эстонского языка",
                    'included' => "Материалы, кофе-брейк, сертификат",
                ]);
            }

            // Создаем SEO
            $eventSeo = EventSeo::create(['event_id' => $event->id]);

            foreach ($languages as $language) {
                EventSeoTranslation::create([
                    'event_seo_id' => $eventSeo->id,
                    'language_id' => $language->id,
                    'meta_title' => "Мероприятие {$event->id} - Кафе эстонского языка",
                    'meta_description' => "Присоединяйтесь к нашему мероприятию по изучению эстонского языка",
                    'meta_keywords' => "эстонский язык, мероприятие, обучение, таллинн",
                ]);
            }

            // Создаем несколько участников
            for ($j = 1; $j <= 3; $j++) {
                EventParticipant::create([
                    'event_id' => $event->id,
                    'first_name' => "Участник{$j}",
                    'last_name' => "Тестовый",
                    'email' => "participant{$j}.event{$event->id}@example.com",
                    'phone' => "+372 5555555{$j}",
                    'status' => $j === 1 ? 'confirmed' : 'pending',
                    'participants_count' => 1,
                    'notes' => "Тестовый участник {$j}",
                ]);
            }
        }
    }
}