<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = [
            ['code' => 'ru', 'name' => 'Русский'],
            ['code' => 'et', 'name' => 'Eesti', 'is_default' => true],
            ['code' => 'uk', 'name' => 'Українська'],
            ['code' => 'en', 'name' => 'English'],
        ];

        foreach ($languages as $lang) {
            Language::create($lang);
        }
    }
}
