<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;


class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Группа "company"
        $companySettings = [
            'company_name' => 'QuickCode OU',
            'company_email' => 'quickcodeou@gmail.com',
            'company_phone' => '+372 5858 128 1',
            'company_address' => 'Kohtla-Järve polikliinik, Ravi 10, Järve linnaosa',
            'logo' => 'assets/img/icons/logo.png',
        ];

        foreach ($companySettings as $key => $value) {
            SiteSetting::group('company')->create([
                'key' => $key,
                'value' => json_encode($value),
            ]);
        }

        // Группа "map"
        $mapSettings = [
            'google' => '',
            'waze' => '',
        ];

        foreach ($mapSettings as $key => $value) {
            SiteSetting::group('map')->create([
                'key' => $key,
                'value' => json_encode($value),
            ]);
        }

        // Группа "hours"
        $hoursSettings = [
            'booking_date_limit' => ['days' => '60', 'active' => true],
            'work_hours' => [
                'monday' => ['start' => '09:00', 'end' => '18:00'],
                'tuesday' => ['start' => '09:00', 'end' => '18:00'],
                'wednesday' => ['start' => '09:00', 'end' => '18:00'],
                'thursday' => ['start' => '09:00', 'end' => '18:00'],
                'friday' => ['start' => '09:00', 'end' => '18:00'],
                'saturday' => ['start' => null, 'end' => null],
                'sunday' => ['start' => null, 'end' => null],
            ],
            'lunch_hours' => ['start' => '12:00', 'end' => '12:30'],
            'fixed_booking_hours' => [
                'value' => ['false'],
                'payload' => ['09:00', '10:00', '11:00', '12:30']
            ],
        ];

        foreach ($hoursSettings as $key => $value) {
            SiteSetting::group('hours')->create([
                'key' => $key,
                'value' => json_encode($value),
            ]);
        }

        // Группа "license"
        SiteSetting::group('license')->create([
            'key' => 'license_key',
            'value' => json_encode('HNFHYDGSJISISNU'),
        ]);

        // Группа "social_media"
        $socialMediaSettings = [
            'social_media_facebook' => 'https://www.facebook.com/share/16NDW7yNoR/?mibextid=wwXIfr',
            'social_media_youtube' => '',
            'social_media_instagram' => 'https://www.instagram.com/goodfeet_ou?igsh=MWd0ZGlqMWk2anBpZA==',
            'social_media_twitter' => '',
            'social_media_whatsapp' => 'https://wa.me/37258581281',
            'social_media_telegram' => 'https://t.me/quickcodeou',
        ];

        foreach ($socialMediaSettings as $key => $value) {
            SiteSetting::group('social_media')->create([
                'key' => $key,
                'value' => json_encode($value),
            ]);
        }

        // Группа "seo"
        $seoSettings = [
            'meta_title' => 'GoodFeet – Профессиональный уход за ногами в Эстонии',
            'meta_description' => 'Добро пожаловать в GoodFeet. Мы предлагаем качественные услуги подологии и педикюра в Kohtla-Järve.',
        ];

        foreach ($seoSettings as $key => $value) {
            SiteSetting::group('seo')->create([
                'key' => $key,
                'value' => json_encode($value),
            ]);
        } 
        // Группа "localization"
        $localizationSettings = [
            'default_language' => 'ru',
            'available_languages' => [
                'en' => [
                    'id' => 1,
                    'code' => 'en',
                    'name' => 'English',
                    'native_name' => 'English',
                    'enabled' => true,
                    'default' => false,
                    'flag' => 'flags/en.svg',
                ],
                'ru' => [
                    'id' => 2,
                    'code' => 'ru',
                    'name' => 'Русский',
                    'native_name' => 'Русский',
                    'enabled' => true,
                    'default' => true,
                    'flag' => 'flags/ru.svg', // Обновляем путь
                ],
                'et' => [
                    'id' => 3,
                    'code' => 'et',
                    'name' => 'Eesti',
                    'native_name' => 'Eesti',
                    'enabled' => true,
                    'default' => false,
                    'flag' => 'flags/et.svg',
                ],
                'uk' => [
                    'id' => 4,
                    'code' => 'uk',
                    'name' => 'Українська',
                    'native_name' => 'Українська',
                    'enabled' => true,
                    'default' => false,
                    'flag' => 'flags/lv.svg',
                ],
                
            ],
            'language_switcher' => true,
            'detect_browser_language' => false,
        ];

        // Скачиваем/копируем флаги в storage/app/public/flags
        $flagImages = [
            'en' => 'public/seeder/flag/en.svg',
            'ru' => 'public/seeder/flag/ru.svg',
            'et' => 'public/seeder/flag/et.svg',
            'lv' => 'public/seeder/flag/lv.svg',
            'fi' => 'public/seeder/flag/lv.svg',
        ];

        foreach ($flagImages as $code => $sourcePath) {
            $destinationPath = storage_path("app/public/flags/{$code}.svg");
            if (!file_exists($destinationPath)) {
                \File::ensureDirectoryExists(dirname($destinationPath));
                \File::copy(base_path($sourcePath), $destinationPath);
            }
        }

        foreach ($localizationSettings as $key => $value) {
            SiteSetting::group('localization')->create([
                'key' => $key,
                'value' => json_encode($value),
            ]);
        }

    }
}
