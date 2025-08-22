<?php
namespace Database\Seeders;

use App\Models\User;
use App\Models\WorkTimeException;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class WorkTimeExceptionSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::pluck('id')->toArray(); // Получаем все ID пользователей
        $personalReasons = [
            'Отпуск', 'Личный приём', 'Семейные дела', 'Командировка',
            'Занятость в клинике', 'Техническое обслуживание',
        ];

        $generalReasons = [
            'Корпоратив', 'Праздник – закрыто', 'Участие в конференции',
            'Новый год', 'Рождество',
        ];


        for ($i = 0; $i < 40; $i++) {
            $date = Carbon::now()->addDays(rand(1, 90)); // Случайная дата в ближайшие 3 месяца

            $fullDay = rand(0, 1) === 1;
            $startTime = $fullDay ? null : Carbon::createFromTime(rand(8, 15), [0, 30][rand(0, 1)])->format('H:i');
            $endTime = $fullDay ? null : Carbon::parse($startTime)->addHours(1)->format('H:i');

            WorkTimeException::create([
                'user_id' => $users[array_rand($users)],
                'date' => $date->toDateString(),
                'start_time' => $startTime,
                'end_time' => $endTime,
                'is_full_day' => $fullDay,
                'repeat_annually' => rand(0, 1),
                'reason' => $personalReasons[array_rand($personalReasons)],
            ]);

            WorkTimeException::create([
                'user_id' => null,
                'date' => $date->toDateString(),
                'start_time' => $startTime,
                'end_time' => $endTime,
                'is_full_day' => $fullDay,
                'repeat_annually' => rand(0, 1),
                'reason' => $generalReasons[array_rand($generalReasons)],
            ]);
        }
    }
}
