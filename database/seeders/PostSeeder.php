<?php

namespace Database\Seeders;

use App\Models\Language;
use App\Models\Post;
use App\Models\PostSeo;
use App\Models\PostSeoTranslation;
use App\Models\PostTranslation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = Language::all();

        for ($i = 1; $i <= 10; $i++) {
            $post = Post::create([
                'slug' => "post-$i",
                'user_id' => rand(1, 5),
                'published_at' => now()->subDays(rand(1, 20)),
            ]);

            foreach ($languages as $lang) {
                $faker = \Faker\Factory::create($lang->code);

                PostTranslation::create([
                    'post_id' => $post->id,
                    'language_id' => $lang->id,
                    'title' => $faker->sentence(5),
                    'description' => $faker->paragraph(3),
                ]);
            }
            $seo = PostSeo::create(['post_id' => $post->id]);

            foreach ($languages as $lang) {
                $faker = \Faker\Factory::create($lang->code);

                PostSeoTranslation::create([
                    'post_seo_id' => $seo->id,
                    'language_id' => $lang->id,
                    'meta_title' => $faker->catchPhrase(),
                    'meta_description' => $faker->text(100),
                    'meta_keywords' => implode(', ', $faker->words(5)),
                ]);
            }

        }
    }
}
