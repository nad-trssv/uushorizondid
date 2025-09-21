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
            [
                'slug' => 'estonian-language-workshops',
                'translations' => [
                    1 => [
                        'title' => 'Мастер-классы по эстонскому языку',
                        'description' => 'Углубленные занятия для изучения эстонского языка с профессиональными преподавателями.',
                        'meta_title' => 'Мастер-классы по эстонскому',
                        'meta_description' => 'Углубленное изучение эстонского языка.',
                        'meta_keywords' => 'эстонский язык, мастер-классы, обучение, практика',
                    ],
                    2 => [
                        'title' => 'Eesti keele töötoad',
                        'description' => 'Sügavama õppimise töötoad koos professionaalsete õpetajatega.',
                        'meta_title' => 'Eesti keele töötoad',
                        'meta_description' => 'Sügavama õppimise töötoad eesti keeles.',
                        'meta_keywords' => 'eesti keel, töötoad, õppimine, praktika',
                    ],
                    3 => [
                        'title' => 'Estonian Language Workshops',
                        'description' => 'In-depth workshops for learning Estonian with professional instructors.',
                        'meta_title' => 'Estonian Language Workshops',
                        'meta_description' => 'In-depth Estonian language learning sessions.',
                        'meta_keywords' => 'Estonian, workshops, learning, practice',
                    ],
                ],
            ],
            [
                'slug' => 'estonian-grammar-classes',
                'translations' => [
                    1 => [
                        'title' => 'Классы по грамматике эстонского языка',
                        'description' => 'Изучите основы и продвинутые аспекты грамматики эстонского языка.',
                        'meta_title' => 'Грамматика эстонского языка',
                        'meta_description' => 'Классы по грамматике для всех уровней.',
                        'meta_keywords' => 'эстонский язык, грамматика, обучение, классы',
                    ],
                    2 => [
                        'title' => 'Eesti keele grammatika tunnid',
                        'description' => 'Õppige eesti keele grammatika põhitõdesid ja edasijõudnute taset.',
                        'meta_title' => 'Eesti keele grammatika',
                        'meta_description' => 'Grammatika tunnid kõigile tasemetele.',
                        'meta_keywords' => 'eesti keel, grammatika, õppimine, tunnid',
                    ],
                    3 => [
                        'title' => 'Estonian Grammar Classes',
                        'description' => 'Learn the basics and advanced aspects of Estonian grammar.',
                        'meta_title' => 'Estonian Grammar',
                        'meta_description' => 'Grammar classes for all levels.',
                        'meta_keywords' => 'Estonian, grammar, learning, classes',
                    ],
                ],
            ],
            [
                'slug' => 'estonian-children-classes',
                'translations' => [
                    1 => [
                        'title' => 'Классы эстонского для детей',
                        'description' => 'Веселые и интерактивные занятия для детей, изучающих эстонский язык.',
                        'meta_title' => 'Эстонский для детей',
                        'meta_description' => 'Интерактивные занятия для детей.',
                        'meta_keywords' => 'эстонский язык, дети, обучение, занятия',
                    ],
                    2 => [
                        'title' => 'Eesti keele tunnid lastele',
                        'description' => 'Lõbusad ja interaktiivsed tunnid lastele, kes õpivad eesti keelt.',
                        'meta_title' => 'Eesti keel lastele',
                        'meta_description' => 'Interaktiivsed tunnid lastele.',
                        'meta_keywords' => 'eesti keel, lapsed, õppimine, tunnid',
                    ],
                    3 => [
                        'title' => 'Estonian Classes for Children',
                        'description' => 'Fun and interactive sessions for kids learning Estonian.',
                        'meta_title' => 'Estonian for Kids',
                        'meta_description' => 'Interactive classes for children.',
                        'meta_keywords' => 'Estonian, kids, learning, classes',
                    ],
                ],
            ],
            [
                'slug' => 'estonian-literature-club',
                'translations' => [
                    1 => [
                        'title' => 'Клуб эстонской литературы',
                        'description' => 'Обсуждаем эстонские книги и авторов в дружеской атмосфере.',
                        'meta_title' => 'Эстонская литература',
                        'meta_description' => 'Клуб любителей эстонской литературы.',
                        'meta_keywords' => 'эстонский язык, литература, книги, клуб',
                    ],
                    2 => [
                        'title' => 'Eesti kirjanduse klubi',
                        'description' => 'Arutame eesti raamatuid ja autoreid sõbralikus õhkkonnas.',
                        'meta_title' => 'Eesti kirjandus',
                        'meta_description' => 'Eesti kirjanduse armastajate klubi.',
                        'meta_keywords' => 'eesti keel, kirjandus, raamatud, klubi',
                    ],
                    3 => [
                        'title' => 'Estonian Literature Club',
                        'description' => 'Discuss Estonian books and authors in a friendly atmosphere.',
                        'meta_title' => 'Estonian Literature',
                        'meta_description' => 'Club for Estonian literature enthusiasts.',
                        'meta_keywords' => 'Estonian, literature, books, club',
                    ],
                ],
            ],
            [
                'slug' => 'estonian-culture-festival',
                'translations' => [
                    1 => [
                        'title' => 'Фестиваль эстонской культуры',
                        'description' => 'Погрузитесь в эстонскую культуру через музыку, танцы и традиции.',
                        'meta_title' => 'Эстонская культура',
                        'meta_description' => 'Фестиваль эстонской культуры для всех.',
                        'meta_keywords' => 'эстонский язык, культура, фестиваль, традиции',
                    ],
                    2 => [
                        'title' => 'Eesti kultuurifestival',
                        'description' => 'Sukelduge eesti kultuuri muusika, tantsude ja traditsioonide kaudu.',
                        'meta_title' => 'Eesti kultuur',
                        'meta_description' => 'Eesti kultuurifestival kõigile.',
                        'meta_keywords' => 'eesti keel, kultuur, festival, traditsioonid',
                    ],
                    3 => [
                        'title' => 'Estonian Culture Festival',
                        'description' => 'Immerse yourself in Estonian culture through music, dance, and traditions.',
                        'meta_title' => 'Estonian Culture',
                        'meta_description' => 'Estonian culture festival for everyone.',
                        'meta_keywords' => 'Estonian, culture, festival, traditions',
                    ],
                ],
            ],
            [
                'slug' => 'estonian-food-workshops',
                'translations' => [
                    1 => [
                        'title' => 'Мастер-классы по эстонской кухне',
                        'description' => 'Научитесь готовить традиционные эстонские блюда с нашими шеф-поварами.',
                        'meta_title' => 'Эстонская кухня',
                        'meta_description' => 'Мастер-классы по эстонской кухне для всех.',
                        'meta_keywords' => 'эстонская кухня, мастер-классы, еда, традиции',
                    ],
                    2 => [
                        'title' => 'Eesti toidu töötoad',
                        'description' => 'Õppige valmistama traditsioonilisi eesti roogasid meie kokkade juhendamisel.',
                        'meta_title' => 'Eesti toit',
                        'meta_description' => 'Eesti toidu töötoad kõigile.',
                        'meta_keywords' => 'eesti toit, töötoad, toit, traditsioonid',
                    ],
                    3 => [
                        'title' => 'Estonian Food Workshops',
                        'description' => 'Learn to cook traditional Estonian dishes with our chefs.',
                        'meta_title' => 'Estonian Food',
                        'meta_description' => 'Estonian food workshops for everyone.',
                        'meta_keywords' => 'Estonian, food, workshops, traditions',
                    ],
                ],
            ],
            [
                'slug' => 'estonian-history-tours',
                'translations' => [
                    1 => [
                        'title' => 'Экскурсии по истории Эстонии',
                        'description' => 'Откройте для себя богатую историю Эстонии с нашими увлекательными экскурсиями.',
                        'meta_title' => 'История Эстонии',
                        'meta_description' => 'Экскурсии по истории Эстонии для всех.',
                        'meta_keywords' => 'эстонская история, экскурсии, культура, традиции',
                    ],
                    2 => [
                        'title' => 'Eesti ajaloo ekskursioonid',
                        'description' => 'Avastage Eesti rikkalik ajalugu meie põnevate ekskursioonidega.',
                        'meta_title' => 'Eesti ajalugu',
                        'meta_description' => 'Eesti ajaloo ekskursioonid kõigile.',
                        'meta_keywords' => 'Eesti ajalugu, ekskursioonid, kultuur, traditsioonid',
                    ],
                    3 => [
                        'title' => 'Estonian History Tours',
                        'description' => 'Discover the rich history of Estonia with our engaging tours.',
                        'meta_title' => 'Estonian History',
                        'meta_description' => 'History tours of Estonia for everyone.',
                        'meta_keywords' => 'Estonian history, tours, culture, traditions',
                    ],
                ],
            ],
            [
                'slug' => 'estonian-art-classes',
                'translations' => [
                    1 => [
                        'title' => 'Классы эстонского искусства',
                        'description' => 'Изучите эстонское искусство и создайте свои собственные произведения.',
                        'meta_title' => 'Эстонское искусство',
                        'meta_description' => 'Классы искусства для всех уровней.',
                        'meta_keywords' => 'эстонское искусство, классы, творчество, обучение',
                    ],
                    2 => [
                        'title' => 'Eesti kunstitunnid',
                        'description' => 'Õppige Eesti kunsti ja looge oma teoseid.',
                        'meta_title' => 'Eesti kunst',
                        'meta_description' => 'Kunstitunnid kõigile tasemetele.',
                        'meta_keywords' => 'Eesti kunst, tunnid, loovus, õppimine',
                    ],
                    3 => [
                        'title' => 'Estonian Art Classes',
                        'description' => 'Learn Estonian art and create your own masterpieces.',
                        'meta_title' => 'Estonian Art',
                        'meta_description' => 'Art classes for all levels.',
                        'meta_keywords' => 'Estonian art, classes, creativity, learning',
                    ],
                ],
            ],
            [
                'slug' => 'estonian-folk-dance',
                'translations' => [
                    1 => [
                        'title' => 'Эстонские народные танцы',
                        'description' => 'Изучите традиционные эстонские танцы с нашими инструкторами.',
                        'meta_title' => 'Народные танцы Эстонии',
                        'meta_description' => 'Танцевальные классы для всех.',
                        'meta_keywords' => 'эстонские танцы, народные, обучение, традиции',
                    ],
                    2 => [
                        'title' => 'Eesti rahvatants',
                        'description' => 'Õppige traditsioonilisi Eesti tantse meie juhendajatega.',
                        'meta_title' => 'Eesti rahvatants',
                        'meta_description' => 'Tantsutunnid kõigile.',
                        'meta_keywords' => 'Eesti tantsud, rahvatants, õppimine, traditsioonid',
                    ],
                    3 => [
                        'title' => 'Estonian Folk Dance',
                        'description' => 'Learn traditional Estonian dances with our instructors.',
                        'meta_title' => 'Estonian Folk Dance',
                        'meta_description' => 'Dance classes for everyone.',
                        'meta_keywords' => 'Estonian dance, folk, learning, traditions',
                    ],
                ],
            ],
            [
                'slug' => 'estonian-poetry-nights',
                'translations' => [
                    1 => [
                        'title' => 'Вечера эстонской поэзии',
                        'description' => 'Наслаждайтесь чтением и обсуждением эстонской поэзии.',
                        'meta_title' => 'Эстонская поэзия',
                        'meta_description' => 'Вечера поэзии для всех.',
                        'meta_keywords' => 'эстонская поэзия, вечера, литература, искусство',
                    ],
                    2 => [
                        'title' => 'Eesti luuleõhtud',
                        'description' => 'Nautige Eesti luule lugemist ja arutelu.',
                        'meta_title' => 'Eesti luule',
                        'meta_description' => 'Luuleõhtud kõigile.',
                        'meta_keywords' => 'Eesti luule, õhtud, kirjandus, kunst',
                    ],
                    3 => [
                        'title' => 'Estonian Poetry Nights',
                        'description' => 'Enjoy readings and discussions of Estonian poetry.',
                        'meta_title' => 'Estonian Poetry',
                        'meta_description' => 'Poetry nights for everyone.',
                        'meta_keywords' => 'Estonian poetry, nights, literature, art',
                    ],
                ],
            ],
            [
                'slug' => 'estonian-film-screenings',
                'translations' => [
                    1 => [
                        'title' => 'Показы эстонских фильмов',
                        'description' => 'Смотрите лучшие эстонские фильмы и обсуждайте их с нами.',
                        'meta_title' => 'Эстонские фильмы',
                        'meta_description' => 'Показы фильмов для всех.',
                        'meta_keywords' => 'эстонские фильмы, показы, кино, культура',
                    ],
                    2 => [
                        'title' => 'Eesti filmide linastused',
                        'description' => 'Vaadake parimaid Eesti filme ja arutage neid meiega.',
                        'meta_title' => 'Eesti filmid',
                        'meta_description' => 'Filmide linastused kõigile.',
                        'meta_keywords' => 'Eesti filmid, linastused, kino, kultuur',
                    ],
                    3 => [
                        'title' => 'Estonian Film Screenings',
                        'description' => 'Watch the best Estonian films and discuss them with us.',
                        'meta_title' => 'Estonian Films',
                        'meta_description' => 'Film screenings for everyone.',
                        'meta_keywords' => 'Estonian films, screenings, cinema, culture',
                    ],
                ],
            ],
            [
                'slug' => 'estonian-language-for-business',
                'translations' => [
                    1 => [
                        'title' => 'Эстонский для бизнеса',
                        'description' => 'Курсы эстонского языка, специально разработанные для делового общения.',
                        'meta_title' => 'Эстонский для бизнеса',
                        'meta_description' => 'Изучайте эстонский для делового общения.',
                        'meta_keywords' => 'эстонский язык, бизнес, обучение, курсы',
                    ],
                    2 => [
                        'title' => 'Eesti keel äriks',
                        'description' => 'Eesti keele kursused, mis on spetsiaalselt loodud ärisuhtluseks.',
                        'meta_title' => 'Eesti keel äriks',
                        'meta_description' => 'Õppige eesti keelt ärisuhtluseks.',
                        'meta_keywords' => 'eesti keel, äri, õppimine, kursused',
                    ],
                    3 => [
                        'title' => 'Estonian for Business',
                        'description' => 'Estonian language courses tailored for business communication.',
                        'meta_title' => 'Estonian for Business',
                        'meta_description' => 'Learn Estonian for professional communication.',
                        'meta_keywords' => 'Estonian, business, learning, courses',
                    ],
                ],
            ],
            [
                'slug' => 'estonian-advanced-grammar',
                'translations' => [
                    1 => [
                        'title' => 'Продвинутая грамматика эстонского языка',
                        'description' => 'Углубленные занятия по грамматике для продвинутых студентов.',
                        'meta_title' => 'Продвинутая грамматика',
                        'meta_description' => 'Грамматические занятия для продвинутых.',
                        'meta_keywords' => 'эстонский язык, грамматика, продвинутый уровень, обучение',
                    ],
                    2 => [
                        'title' => 'Eesti keele edasijõudnute grammatika',
                        'description' => 'Sügavama õppimise grammatika tunnid edasijõudnutele.',
                        'meta_title' => 'Edasijõudnute grammatika',
                        'meta_description' => 'Grammatika tunnid edasijõudnutele.',
                        'meta_keywords' => 'eesti keel, grammatika, edasijõudnud, õppimine',
                    ],
                    3 => [
                        'title' => 'Advanced Estonian Grammar',
                        'description' => 'In-depth grammar lessons for advanced learners.',
                        'meta_title' => 'Advanced Grammar',
                        'meta_description' => 'Grammar lessons for advanced students.',
                        'meta_keywords' => 'Estonian, grammar, advanced, learning',
                    ],
                ],
            ],
            [
                'slug' => 'estonian-idioms-and-phrases',
                'translations' => [
                    1 => [
                        'title' => 'Эстонские идиомы и фразы',
                        'description' => 'Изучите популярные идиомы и фразы для повседневного общения.',
                        'meta_title' => 'Идиомы и фразы',
                        'meta_description' => 'Эстонские идиомы для общения.',
                        'meta_keywords' => 'эстонский язык, идиомы, фразы, общение',
                    ],
                    2 => [
                        'title' => 'Eesti idioomid ja fraasid',
                        'description' => 'Õppige populaarseid idioome ja fraase igapäevaseks suhtlemiseks.',
                        'meta_title' => 'Idioomid ja fraasid',
                        'meta_description' => 'Eesti idioomid suhtlemiseks.',
                        'meta_keywords' => 'eesti keel, idioomid, fraasid, suhtlemine',
                    ],
                    3 => [
                        'title' => 'Estonian Idioms and Phrases',
                        'description' => 'Learn popular idioms and phrases for everyday communication.',
                        'meta_title' => 'Idioms and Phrases',
                        'meta_description' => 'Estonian idioms for communication.',
                        'meta_keywords' => 'Estonian, idioms, phrases, communication',
                    ],
                ],
            ],
            [
                'slug' => 'estonian-pronunciation-workshop',
                'translations' => [
                    1 => [
                        'title' => 'Мастер-класс по произношению',
                        'description' => 'Улучшите свое произношение эстонского языка с нашими экспертами.',
                        'meta_title' => 'Произношение эстонского',
                        'meta_description' => 'Мастер-класс по произношению для всех уровней.',
                        'meta_keywords' => 'эстонский язык, произношение, мастер-класс, обучение',
                    ],
                    2 => [
                        'title' => 'Eesti häälduse töötuba',
                        'description' => 'Parandage oma eesti keele hääldust meie ekspertidega.',
                        'meta_title' => 'Eesti hääldus',
                        'meta_description' => 'Häälduse töötuba kõigile tasemetele.',
                        'meta_keywords' => 'eesti keel, hääldus, töötuba, õppimine',
                    ],
                    3 => [
                        'title' => 'Estonian Pronunciation Workshop',
                        'description' => 'Improve your Estonian pronunciation with our experts.',
                        'meta_title' => 'Estonian Pronunciation',
                        'meta_description' => 'Pronunciation workshop for all levels.',
                        'meta_keywords' => 'Estonian, pronunciation, workshop, learning',
                    ],
                ],
            ],
            [
                'slug' => 'estonian-writing-skills',
                'translations' => [
                    1 => [
                        'title' => 'Навыки письма на эстонском',
                        'description' => 'Развивайте свои навыки письма на эстонском языке.',
                        'meta_title' => 'Письмо на эстонском',
                        'meta_description' => 'Курсы письма для всех уровней.',
                        'meta_keywords' => 'эстонский язык, письмо, обучение, навыки',
                    ],
                    2 => [
                        'title' => 'Eesti keele kirjutamisoskused',
                        'description' => 'Arendage oma eesti keele kirjutamisoskusi.',
                        'meta_title' => 'Kirjutamisoskused',
                        'meta_description' => 'Kirjutamiskursused kõigile tasemetele.',
                        'meta_keywords' => 'eesti keel, kirjutamine, õppimine, oskused',
                    ],
                    3 => [
                        'title' => 'Estonian Writing Skills',
                        'description' => 'Develop your Estonian writing skills.',
                        'meta_title' => 'Writing Skills',
                        'meta_description' => 'Writing courses for all levels.',
                        'meta_keywords' => 'Estonian, writing, learning, skills',
                    ],
                ],
            ],
            [
                'slug' => 'estonian-speaking-club',
                'translations' => [
                    1 => [
                        'title' => 'Разговорный клуб эстонского языка',
                        'description' => 'Практикуйте разговорный эстонский в дружеской обстановке.',
                        'meta_title' => 'Разговорный клуб',
                        'meta_description' => 'Клуб для практики эстонского языка.',
                        'meta_keywords' => 'эстонский язык, разговорный клуб, практика, общение',
                    ],
                    2 => [
                        'title' => 'Eesti keele vestlusklubi',
                        'description' => 'Harjutage eesti keelt sõbralikus keskkonnas.',
                        'meta_title' => 'Vestlusklubi',
                        'meta_description' => 'Klubi eesti keele praktiseerimiseks.',
                        'meta_keywords' => 'eesti keel, vestlusklubi, praktika, suhtlemine',
                    ],
                    3 => [
                        'title' => 'Estonian Speaking Club',
                        'description' => 'Practice conversational Estonian in a friendly environment.',
                        'meta_title' => 'Speaking Club',
                        'meta_description' => 'Club for practicing Estonian.',
                        'meta_keywords' => 'Estonian, speaking club, practice, communication',
                    ],
                ],
            ],
            [
                'slug' => 'estonian-cultural-immersion',
                'translations' => [
                    1 => [
                        'title' => 'Погружение в эстонскую культуру',
                        'description' => 'Изучайте язык через культуру и традиции Эстонии.',
                        'meta_title' => 'Культурное погружение',
                        'meta_description' => 'Погружение в эстонскую культуру и язык.',
                        'meta_keywords' => 'эстонский язык, культура, традиции, обучение',
                    ],
                    2 => [
                        'title' => 'Eesti kultuuriline sukeldumine',
                        'description' => 'Õppige keelt Eesti kultuuri ja traditsioonide kaudu.',
                        'meta_title' => 'Kultuuriline sukeldumine',
                        'meta_description' => 'Sukeldumine Eesti kultuuri ja keelde.',
                        'meta_keywords' => 'eesti keel, kultuur, traditsioonid, õppimine',
                    ],
                    3 => [
                        'title' => 'Estonian Cultural Immersion',
                        'description' => 'Learn the language through Estonian culture and traditions.',
                        'meta_title' => 'Cultural Immersion',
                        'meta_description' => 'Immersion into Estonian culture and language.',
                        'meta_keywords' => 'Estonian, culture, traditions, learning',
                    ],
                ],
            ],
            [
                'slug' => 'estonian-language-games',
                'translations' => [
                    1 => [
                        'title' => 'Игры для изучения эстонского',
                        'description' => 'Веселые и интерактивные игры для изучения языка.',
                        'meta_title' => 'Игры для изучения языка',
                        'meta_description' => 'Изучайте эстонский через игры.',
                        'meta_keywords' => 'эстонский язык, игры, обучение, практика',
                    ],
                    2 => [
                        'title' => 'Keeleõppemängud',
                        'description' => 'Lõbusad ja interaktiivsed mängud keele õppimiseks.',
                        'meta_title' => 'Keeleõppemängud',
                        'meta_description' => 'Õppige eesti keelt mängude kaudu.',
                        'meta_keywords' => 'eesti keel, mängud, õppimine, praktika',
                    ],
                    3 => [
                        'title' => 'Language Learning Games',
                        'description' => 'Fun and interactive games for language learning.',
                        'meta_title' => 'Language Games',
                        'meta_description' => 'Learn Estonian through games.',
                        'meta_keywords' => 'Estonian, games, learning, practice',
                    ],
                ],
            ],
            [
                'slug' => 'estonian-virtual-tours',
                'translations' => [
                    1 => [
                        'title' => 'Виртуальные туры по Эстонии',
                        'description' => 'Изучайте язык, путешествуя по Эстонии онлайн.',
                        'meta_title' => 'Виртуальные туры',
                        'meta_description' => 'Откройте Эстонию через виртуальные туры.',
                        'meta_keywords' => 'эстонский язык, виртуальные туры, обучение, путешествия',
                    ],
                    2 => [
                        'title' => 'Virtuaalsed ekskursioonid Eestis',
                        'description' => 'Õppige keelt, reisides Eestis veebis.',
                        'meta_title' => 'Virtuaalsed ekskursioonid',
                        'meta_description' => 'Avastage Eestit virtuaalsete ekskursioonide kaudu.',
                        'meta_keywords' => 'eesti keel, virtuaalsed ekskursioonid, õppimine, reisimine',
                    ],
                    3 => [
                        'title' => 'Estonian Virtual Tours',
                        'description' => 'Learn the language while exploring Estonia online.',
                        'meta_title' => 'Virtual Tours',
                        'meta_description' => 'Discover Estonia through virtual tours.',
                        'meta_keywords' => 'Estonian, virtual tours, learning, travel',
                    ],
                ],
            ],
            [
                'slug' => 'estonian-language-for-travelers',
                'translations' => [
                    1 => [
                        'title' => 'Эстонский для путешественников',
                        'description' => 'Изучите ключевые фразы для путешествий по Эстонии.',
                        'meta_title' => 'Эстонский для путешествий',
                        'meta_description' => 'Курсы для путешественников.',
                        'meta_keywords' => 'эстонский язык, путешествия, обучение, фразы',
                    ],
                    2 => [
                        'title' => 'Eesti keel reisijatele',
                        'description' => 'Õppige olulisi fraase reisimiseks Eestis.',
                        'meta_title' => 'Eesti keel reisijatele',
                        'meta_description' => 'Kursused reisijatele.',
                        'meta_keywords' => 'eesti keel, reisimine, õppimine, fraasid',
                    ],
                    3 => [
                        'title' => 'Estonian for Travelers',
                        'description' => 'Learn essential phrases for traveling in Estonia.',
                        'meta_title' => 'Estonian for Travelers',
                        'meta_description' => 'Courses for travelers.',
                        'meta_keywords' => 'Estonian, travel, learning, phrases',
                    ],
                ],
            ],
            [
                'slug' => 'estonian-slang-and-colloquialisms',
                'translations' => [
                    1 => [
                        'title' => 'Эстонский сленг и разговорные выражения',
                        'description' => 'Изучите популярные сленговые выражения для повседневного общения.',
                        'meta_title' => 'Эстонский сленг',
                        'meta_description' => 'Сленг и разговорные выражения.',
                        'meta_keywords' => 'эстонский язык, сленг, выражения, общение',
                    ],
                    2 => [
                        'title' => 'Eesti släng ja kõnekeelsed väljendid',
                        'description' => 'Õppige populaarseid slängiväljendeid igapäevaseks suhtlemiseks.',
                        'meta_title' => 'Eesti släng',
                        'meta_description' => 'Släng ja kõnekeelsed väljendid.',
                        'meta_keywords' => 'eesti keel, släng, väljendid, suhtlemine',
                    ],
                    3 => [
                        'title' => 'Estonian Slang and Colloquialisms',
                        'description' => 'Learn popular slang expressions for everyday communication.',
                        'meta_title' => 'Estonian Slang',
                        'meta_description' => 'Slang and colloquial expressions.',
                        'meta_keywords' => 'Estonian, slang, expressions, communication',
                    ],
                ],
            ],
            [
                'slug' => 'estonian-eco-tourism',
                'translations' => [
                    1 => [
                        'title' => 'Экотуризм в Эстонии',
                        'description' => 'Откройте для себя природные красоты Эстонии.',
                        'meta_title' => 'Эстонский экотуризм',
                        'meta_description' => 'Экотуризм и природа.',
                        'meta_keywords' => 'эстонский язык, экотуризм, природа, путешествия',
                    ],
                    2 => [
                        'title' => 'Eesti ökoturism',
                        'description' => 'Avastage Eesti looduskaunid paigad.',
                        'meta_title' => 'Eesti ökoturism',
                        'meta_description' => 'Ökoturism ja loodus.',
                        'meta_keywords' => 'eesti keel, ökoturism, loodus, reisimine',
                    ],
                    3 => [
                        'title' => 'Estonian Eco-Tourism',
                        'description' => 'Discover the natural beauty of Estonia.',
                        'meta_title' => 'Estonian Eco-Tourism',
                        'meta_description' => 'Eco-tourism and nature.',
                        'meta_keywords' => 'Estonian, eco-tourism, nature, travel',
                    ],
                ],
            ],
            [
                'slug' => 'estonian-language-for-healthcare',
                'translations' => [
                    1 => [
                        'title' => 'Эстонский для медицины',
                        'description' => 'Курсы для медицинских работников.',
                        'meta_title' => 'Эстонский для медицины',
                        'meta_description' => 'Язык для медицинских работников.',
                        'meta_keywords' => 'эстонский язык, медицина, обучение, курсы',
                    ],
                    2 => [
                        'title' => 'Eesti keel meditsiinile',
                        'description' => 'Kursused meditsiinitöötajatele.',
                        'meta_title' => 'Eesti keel meditsiinile',
                        'meta_description' => 'Keel meditsiinitöötajatele.',
                        'meta_keywords' => 'eesti keel, meditsiin, õppimine, kursused',
                    ],
                    3 => [
                        'title' => 'Estonian for Healthcare',
                        'description' => 'Courses for medical professionals.',
                        'meta_title' => 'Estonian for Healthcare',
                        'meta_description' => 'Language for medical professionals.',
                        'meta_keywords' => 'Estonian, healthcare, learning, courses',
                    ],
                ],
            ],
            [
                'slug' => 'estonian-language-for-it',
                'translations' => [
                    1 => [
                        'title' => 'Эстонский для IT',
                        'description' => 'Курсы для специалистов в сфере IT.',
                        'meta_title' => 'Эстонский для IT',
                        'meta_description' => 'Язык для IT-специалистов.',
                        'meta_keywords' => 'эстонский язык, IT, обучение, курсы',
                    ],
                    2 => [
                        'title' => 'Eesti keel IT-le',
                        'description' => 'Kursused IT-spetsialistidele.',
                        'meta_title' => 'Eesti keel IT-le',
                        'meta_description' => 'Keel IT-spetsialistidele.',
                        'meta_keywords' => 'eesti keel, IT, õppimine, kursused',
                    ],
                    3 => [
                        'title' => 'Estonian for IT',
                        'description' => 'Courses for IT professionals.',
                        'meta_title' => 'Estonian for IT',
                        'meta_description' => 'Language for IT professionals.',
                        'meta_keywords' => 'Estonian, IT, learning, courses',
                    ],
                ],
            ],
            [
                'slug' => 'estonian-language-for-hospitality',
                'translations' => [
                    1 => [
                        'title' => 'Эстонский для гостиничного бизнеса',
                        'description' => 'Курсы для работников гостиничного бизнеса.',
                        'meta_title' => 'Эстонский для гостиниц',
                        'meta_description' => 'Язык для гостиничного бизнеса.',
                        'meta_keywords' => 'эстонский язык, гостиницы, обучение, курсы',
                    ],
                    2 => [
                        'title' => 'Eesti keel hotellindusele',
                        'description' => 'Kursused hotellinduse töötajatele.',
                        'meta_title' => 'Eesti keel hotellindusele',
                        'meta_description' => 'Keel hotellinduse töötajatele.',
                        'meta_keywords' => 'eesti keel, hotellindus, õppimine, kursused',
                    ],
                    3 => [
                        'title' => 'Estonian for Hospitality',
                        'description' => 'Courses for hospitality workers.',
                        'meta_title' => 'Estonian for Hospitality',
                        'meta_description' => 'Language for hospitality workers.',
                        'meta_keywords' => 'Estonian, hospitality, learning, courses',
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
