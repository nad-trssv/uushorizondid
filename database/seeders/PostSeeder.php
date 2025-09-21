<?php

namespace Database\Seeders;

use App\Models\Language;
use App\Models\Post;
use App\Models\PostSeo;
use App\Models\PostSeoTranslation;
use App\Models\PostTranslation;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = Language::all()->keyBy('id');

        $posts = [
            [
                'slug' => 'estonian-language-cafe-welcome',
                'translations' => [
                    1 => [ // Русский
                        'title' => 'Добро пожаловать в наше кафе эстонского языка!',
                        'description' => 'Узнайте, как весело и вкусно учить эстонский в уютной атмосфере нашего кафе. Курсы, разговорные клубы и свежая выпечка ждут вас!',
                        'meta_title' => 'Эстонский язык с кофе',
                        'meta_description' => 'Учим эстонский в кафе: легко, вкусно и интересно!',
                        'meta_keywords' => 'эстонский язык, кафе, изучение, обучение, Таллинн',
                    ],
                    2 => [ // Eesti
                        'title' => 'Tere tulemast meie eesti keele kohvikusse!',
                        'description' => 'Avastage, kuidas meie hubases kohvikus on eesti keele õppimine lõbus ja maitsev. Keelekursused, vestlusringid ja värske küpsetis ootavad teid!',
                        'meta_title' => 'Eesti keel kohvitassiga',
                        'meta_description' => 'Õpi eesti keelt mõnusas kohvikus!',
                        'meta_keywords' => 'eesti keel, kohvik, õppimine, keelekursused, Tallinn',
                    ],
                    3 => [ // English
                        'title' => 'Welcome to our Estonian Language Café!',
                        'description' => 'Discover how fun and delicious learning Estonian can be in our cozy café. Courses, speaking clubs, and fresh pastries await!',
                        'meta_title' => 'Learn Estonian Over Coffee',
                        'meta_description' => 'Study Estonian in a relaxed café atmosphere.',
                        'meta_keywords' => 'Estonian, language café, learning, courses, Tallinn',
                    ],
                ],
            ],
            [
                'slug' => 'estonian-native-speaker-lessons',
                'translations' => [
                    1 => [
                        'title' => 'Новинка: Уроки с носителем в кафе',
                        'description' => 'Погружение в эстонский с опытными носителями языка — теперь прямо в вашем любимом кафе!',
                        'meta_title' => 'Эстонский с носителем',
                        'meta_description' => 'Уроки эстонского с носителем в кафе — лучший способ учиться!',
                        'meta_keywords' => 'эстонский, носитель, уроки, кафе, обучение',
                    ],
                    2 => [
                        'title' => 'Uudis: Keeletunnid emakeelega kohvikus',
                        'description' => 'Sukelduge eesti keelde koos kogenud emakeelsete õpetajatega — nüüd otse teie lemmikkohvikus!',
                        'meta_title' => 'Eesti keele tunnid emakeelega',
                        'meta_description' => 'Keeletunnid kohvikus koos emakeelega õpetajaga.',
                        'meta_keywords' => 'eesti keel, emakeel, õpetaja, kohvik, õppimine',
                    ],
                    3 => [
                        'title' => 'New: Native Speaker Lessons at the Café',
                        'description' => 'Immerse in Estonian with experienced native speakers — now available in your favorite café!',
                        'meta_title' => 'Estonian with Native Speakers',
                        'meta_description' => 'Learn Estonian from native speakers in a café setting.',
                        'meta_keywords' => 'Estonian, native speaker, lessons, café, learn',
                    ],
                ],
            ],
            [
                'slug' => 'estonian-conversation-club-friday',
                'translations' => [
                    1 => [
                        'title' => 'Разговорный клуб по пятницам',
                        'description' => 'Присоединяйтесь к открытому разговорному клубу по эстонскому языку каждую пятницу в 18:00. Без стресса, только живое общение!',
                        'meta_title' => 'Практикуй эстонский с нами',
                        'meta_description' => 'Разговорный клуб по пятницам — учим язык в реальной беседе.',
                        'meta_keywords' => 'эстонский, разговорный клуб, пятница, практика, кафе',
                    ],
                    2 => [
                        'title' => 'Vestlusring igal reedel',
                        'description' => 'Tule osale eesti keele vestlusringis igal reedel kell 18:00. Stressivaba suhtlus ja sõbralik õhkkond!',
                        'meta_title' => 'Harjuta eesti keelt meiega',
                        'meta_description' => 'Vestlusring reedeti – räägi ja õpi keelt loomulikult.',
                        'meta_keywords' => 'eesti keel, vestlusring, reede, praktika, kohvik',
                    ],
                    3 => [
                        'title' => 'Conversation Club Every Friday',
                        'description' => 'Join our open Estonian conversation club every Friday at 6 PM. Stress-free speaking and friendly people!',
                        'meta_title' => 'Practice Estonian with Us',
                        'meta_description' => 'Learn Estonian in real conversations – every Friday.',
                        'meta_keywords' => 'Estonian, conversation club, Friday, speaking, café',
                    ],
                ],
            ],
            [
                'slug' => 'estonian-language-menu-cafe',
                'translations' => [
                    1 => [
                        'title' => 'Меню на эстонском — учись, заказывая!',
                        'description' => 'Каждое блюдо в нашем кафе сопровождается эстонскими подписями. Практикуйте язык даже во время заказа!',
                        'meta_title' => 'Учим эстонский через меню',
                        'meta_description' => 'Практикуй язык с нашим интерактивным меню.',
                        'meta_keywords' => 'эстонский язык, меню, кафе, изучение, практика',
                    ],
                    2 => [
                        'title' => 'Eesti keel menüüs — õpi tellides!',
                        'description' => 'Iga roog meie menüüs on eesti keeles kirjas. Harjuta keelt tellimise ajal!',
                        'meta_title' => 'Õpi eesti keelt menüüst',
                        'meta_description' => 'Keelepraktika otse menüüst – proovi kohe!',
                        'meta_keywords' => 'eesti keel, menüü, kohvik, õppimine, praktika',
                    ],
                    3 => [
                        'title' => 'Menu in Estonian – Learn While Ordering!',
                        'description' => 'Every item on our menu includes Estonian labels. Practice vocabulary during your meal!',
                        'meta_title' => 'Learn Estonian from the Menu',
                        'meta_description' => 'Improve Estonian with our interactive food menu.',
                        'meta_keywords' => 'Estonian, menu, café, vocabulary, learning',
                    ],
                ],
            ],
            [
                'slug' => 'estonian-theme-nights-music',
                'translations' => [
                    1 => [
                        'title' => 'Тематические вечера: музыка и эстонский',
                        'description' => 'Проводим тематические вечера с эстонской музыкой, поэзией и языковой практикой. Открой для себя культуру и язык!',
                        'meta_title' => 'Эстонский через культуру',
                        'meta_description' => 'Изучаем язык через музыку и искусство.',
                        'meta_keywords' => 'эстонский язык, культура, музыка, вечер, кафе',
                    ],
                    2 => [
                        'title' => 'Temaatilised õhtud: muusika ja eesti keel',
                        'description' => 'Korraldame õhtuid eesti muusika, luule ja keelepraktikaga. Avasta keel ja kultuur korraga!',
                        'meta_title' => 'Eesti keel kultuuri kaudu',
                        'meta_description' => 'Keeleõpe läbi kunsti ja muusika.',
                        'meta_keywords' => 'eesti keel, kultuur, muusika, õhtu, kohvik',
                    ],
                    3 => [
                        'title' => 'Theme Nights: Music and Estonian',
                        'description' => 'Enjoy evenings with Estonian music, poetry, and language immersion. Explore the culture through the language!',
                        'meta_title' => 'Learn Estonian Through Culture',
                        'meta_description' => 'Practice Estonian with music, poetry, and art.',
                        'meta_keywords' => 'Estonian, culture, music, evening, café',
                    ],
                ],
            ],
        ];

        foreach ($posts as $postData) {
            $post = Post::create([
                'slug' => $postData['slug'],
                'user_id' => 1,
                'status' => 'published',
                'views' => rand(10, 100),
                'published_at' => now(),
            ]);

            $seo = PostSeo::create(['post_id' => $post->id]);

            foreach ($postData['translations'] as $langId => $data) {
                PostTranslation::create([
                    'post_id' => $post->id,
                    'language_id' => $langId,
                    'title' => $data['title'],
                    'description' => $data['description'],
                ]);

                PostSeoTranslation::create([
                    'post_seo_id' => $seo->id,
                    'language_id' => $langId,
                    'meta_title' => $data['meta_title'],
                    'meta_description' => $data['meta_description'],
                    'meta_keywords' => $data['meta_keywords'],
                ]);
            }
        }
    }
}
