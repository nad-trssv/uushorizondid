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
            ['code' => 'ru', 'name' => 'Русский', 'native_name' => 'Русский', 'iso' => 'ru-RU', 'enabled' => true, 'default' => false, 'flag' => 'flags/ru.svg'],
            ['code' => 'et', 'name' => 'Eesti', 'native_name' => 'Eesti', 'iso' => 'et-EE', 'enabled' => true, 'default' => true, 'flag' => 'flags/et.svg'],
            ['code' => 'en', 'name' => 'English', 'native_name' => 'English', 'iso' => 'en-US', 'enabled' => true, 'default' => false, 'flag' => 'flags/en.svg'],
            ['code' => 'uk', 'name' => 'Українська', 'native_name' => 'Українська', 'iso' => 'uk-UA', 'enabled' => true, 'default' => false, 'flag' => 'flags/lv.svg'],
        ];

        foreach ($languages as $lang) {
            Language::create($lang);
        }
    }
}
