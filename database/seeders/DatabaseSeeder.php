<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\SiteSetting;
use Illuminate\Database\Seeder;
use Illuminate\Queue\Worker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            ServiceSeeder::class,
            SiteSettingSeeder::class,
            WorkTimeExceptionSeeder::class,
            AppointmentsSeeder::class,
            ServiceMasterSeeder::class,
            LanguageSeeder::class,
            PostSeeder::class,
            CommentSeeder::class,
            EventSeeder::class,
        ]);
    }
}
