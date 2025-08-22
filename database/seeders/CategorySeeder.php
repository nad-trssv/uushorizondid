<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\CategoryTranslation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $categories = [
            [
                'translations' => [
                    'en' => 'Hair Services',
                    'ru' => 'Услуги для волос',
                    'et' => 'Juuksehooldus',
                    'lv' => 'Matu kopšana',
                ],
                'children' => [
                    ['en' => 'Haircut', 'ru' => 'Стрижка', 'et' => 'Juukselõikus', 'lv' => 'Matu griešana'],
                    ['en' => 'Hair Coloring', 'ru' => 'Окрашивание волос', 'et' => 'Juuste värvimine', 'lv' => 'Matu krāsošana'],
                    ['en' => 'Hair Treatment', 'ru' => 'Уход за волосами', 'et' => 'Juuksehooldus', 'lv' => 'Matu kopšana'],
                ]
            ],
            [
                'translations' => [
                    'en' => 'Nail Services',
                    'ru' => 'Ногтевой сервис',
                    'et' => 'Küünte hooldus',
                    'lv' => 'Nagu kopšana',
                ],
                'children' => [
                    ['en' => 'Manicure', 'ru' => 'Маникюр', 'et' => 'Maniküür', 'lv' => 'Manikīrs'],
                    ['en' => 'Pedicure', 'ru' => 'Педикюр', 'et' => 'Pediküür', 'lv' => 'Pedikīrs'],
                    ['en' => 'Nail Extensions', 'ru' => 'Наращивание ногтей', 'et' => 'Küünte pikendamine', 'lv' => 'Nagu pieaudzēšana'],
                ]
            ],
            [
                'translations' => [
                    'en' => 'Face Treatments',
                    'ru' => 'Уход за лицом',
                    'et' => 'Näohooldus',
                    'lv' => 'Sejas kopšana',
                ],
                'children' => [
                    ['en' => 'Facial Cleansing', 'ru' => 'Очищение лица', 'et' => 'Näopuhastus', 'lv' => 'Sejas attīrīšana'],
                    ['en' => 'Eyebrow Shaping', 'ru' => 'Коррекция бровей', 'et' => 'Kulmude kujundamine', 'lv' => 'Uzacu korekcija'],
                    ['en' => 'Lash Extensions', 'ru' => 'Наращивание ресниц', 'et' => 'Ripsmepikendused', 'lv' => 'Skropstu pieaudzēšana'],
                ]
            ],
            [
                'translations' => [
                    'en' => 'Body Treatments',
                    'ru' => 'Уход за телом',
                    'et' => 'Kehahooldus',
                    'lv' => 'Ķermeņa kopšana',
                ],
                'children' => [
                    [
                        'en' => 'Waxing', 'ru' => 'Ваксинг', 'et' => 'Vahatamine', 'lv' => 'Vaksācija',
                        'children' => [
                            ['en' => 'Relaxing Massage', 'ru' => 'Расслабляющий массаж', 'et' => 'Lõõgastav massaaž', 'lv' => 'Relaksējošā masāža'],
                            ['en' => 'Therapeutic Massage', 'ru' => 'Терапевтический массаж', 'et' => 'Teraapiline massaaž', 'lv' => 'Terapijas masāža'],
                        ],
                    ],
                    [
                        'en' => 'Nail Art', 'ru' => 'Ногтевой арт', 'et' => 'Küünte kunst', 'lv' => 'Nagu māksla',
                        'children' => [
                            ['en' => 'Gel Nails', 'ru' => 'Гелевые ногти', 'et' => 'Geelküüned', 'lv' => 'Gēla nagi', 
                                'children' => [],
                            ],
                            ['en' => 'Acrylic Nails', 'ru' => 'Акриловые ногти', 'et' => 'Akrüülküüned', 'lv' => 'Akrila nagi', 
                                'children' => [],
                            ],
                        ],
                    ],
                    [
                        'en' => 'Body Scrubs', 'ru' => 'Скрабы для тела', 'et' => 'Kehakoorimine', 'lv' => 'Ķermeņa skrubji',
                        'children' => [
                            ['en' => 'Exfoliating Scrub', 'ru' => 'Отшелушивающий скраб', 'et' => 'Kooriv koorija', 'lv' => 'Eksfoliējošs skrubis'],
                            ['en' => 'Moisturizing Scrub', 'ru' => 'Увлажняющий скраб', 'et' => 'Niisutav koorija', 'lv' => 'Mitrinošs skrubis'],
                        ],
                    ],
                ]
            ],
        ];

        
        foreach ($categories as $catData) {
            $this->createCategoryWithChildren($catData);
        }

    $rootCategories = Category::whereNull('parent_id')->orderBy('id')->get();

    foreach ($rootCategories as $index => $category) {
        $category->order = $index + 1;
        $category->save();
    }

    $parentCategories = Category::whereNotNull('parent_id')->pluck('parent_id')->unique();

    foreach ($parentCategories as $parentId) {
        $children = Category::where('parent_id', $parentId)->orderBy('id')->get();

        foreach ($children as $index => $child) {
            $child->order = $index + 1;
            $child->save();
        }
    }
    }
    private function createCategoryWithChildren(array $data, $parentId = null)
    {
        // Создаём категорию
        $category = Category::create([
            'parent_id' => $parentId,
        ]);

        // Добавляем переводы
        foreach (['en', 'ru', 'et', 'lv'] as $locale) {
            if (!isset($data[$locale]) && isset($data['translations'][$locale])) {
                // верхний уровень
                $name = $data['translations'][$locale];
                $description = $data['translations'][$locale] . ' services';
            } else {
                // подкатегории
                $name = $data[$locale];
                $description = $data[$locale] . ' service';
            }

            CategoryTranslation::create([
                'category_id' => $category->id,
                'locale' => $locale,
                'name' => $name,
                'description' => $description,
            ]);
        }

        // Рекурсивно создаём детей
        if (isset($data['children']) && is_array($data['children'])) {
            foreach ($data['children'] as $childData) {
                $this->createCategoryWithChildren($childData, $category->id);
            }
        }
    }
}
