<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AppointmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $startDate = Carbon::create(2025, 6, 1);
        $endDate = Carbon::create(2025, 8, 31);
        $serviceDurations = [60, 90, 120];

        while ($startDate->lte($endDate)) {
            // Только будние дни
            if ($startDate->isWeekday()) {
                $appointments = [];
                $startTime = Carbon::createFromTime(9, 0);
                $endTime = Carbon::createFromTime(16, 0);
                $current = $startTime->copy();

                while ($current->lt($endTime)) {
                    $duration = collect($serviceDurations)->random();
                    $next = $current->copy()->addMinutes($duration);

                    if ($next->gt($endTime)) break;

                    $appointments[] = [
                        'user_id' => 1,
                        'service_id' => rand(1, 10),
                        'client_name' => 'Client' . rand(100, 999),
                        'client_lastname' => 'Surname' . rand(100, 999),
                        'client_phone' => '+7000' . rand(100000, 999999),
                        'client_email' => null,
                        'price' => rand(1000, 5000),
                        'status' => 1,
                        'appointment_date' => $startDate->toDateString(),
                        'appointment_start' => $current->format('H:i'),
                        'appointment_end' => $next->format('H:i'),
                        'description' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    $current = $next;
                    if (count($appointments) >= 6) break;
                }

                if (count($appointments) >= 5) {
                    DB::table('appointments')->insert($appointments);
                }
            }

            $startDate->addDay();
        }
    }
}
