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
            ['code' => 'ru', 'name' => 'Русский', 'native_name' => 'Русский', 'enabled' => true, 'default' => true, 'flag' => 'flags/ru.svg'],
            ['code' => 'et', 'name' => 'Eesti', 'native_name' => 'Eesti', 'enabled' => true, 'default' => false, 'flag' => 'flags/et.svg'],
            ['code' => 'en', 'name' => 'English', 'native_name' => 'English', 'enabled' => true, 'default' => false, 'flag' => 'flags/en.svg'],
            ['code' => 'uk', 'name' => 'Українська', 'native_name' => 'Українська', 'enabled' => true, 'default' => false, 'flag' => 'flags/lv.svg'],
        ];

        foreach ($languages as $lang) {
            Language::create($lang);
        }
    }
}
