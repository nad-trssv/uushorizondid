<?php

namespace Database\Seeders;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run()
    {
        $startDate = Carbon::now()->startOfDay();

        for ($i = 0; $i < 30; $i++) {
            $dayOffset = rand(0, 90); // в пределах 3 месяцев
            $start = $startDate->copy()->addDays($dayOffset)->setTime(rand(8, 18), 0);
            $end = $start->copy()->addHour();

            Event::create([
                'title' => 'Событие #' . ($i + 1),
                'description' => 'Описание события номер ' . ($i + 1),
                'start_at' => $start,
                'end_at' => $end,
                'location' => 'Кабинет ' . (rand(1, 5)),
                'all_day' => false,
                'repeat_yearly' => false,
            ]);
        }

        // Добавим несколько повторяющихся дней рождения и праздников
        Event::create([
            'title' => 'День Рождения директора',
            'description' => 'Отметим день рождения директора компании',
            'start_at' => Carbon::createFromDate(null, 5, 10)->startOfDay(),
            'end_at' => null,
            'location' => null,
            'all_day' => true,
            'repeat_yearly' => true,
        ]);

        Event::create([
            'title' => 'Новый год',
            'description' => 'Праздник Новый год, выходной',
            'start_at' => Carbon::createFromDate(null, 1, 1)->startOfDay(),
            'end_at' => null,
            'location' => null,
            'all_day' => true,
            'repeat_yearly' => true,
        ]);
    }
}
