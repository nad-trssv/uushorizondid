<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comment;
use App\Models\Post;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        $posts = Post::all();
        
        // Комментарии для каждого поста
        $commentsData = [
            // Пост 1 - Estonian Language Café
            1 => [
                [
                    'name' => 'Anna Kask',
                    'rating' => 5,
                    'content' => 'Отличная идея! Наконец-то можно изучать эстонский язык в уютной атмосфере. Когда открытие?',
                    'approved' => 1,
                ],
                [
                    'name' => 'Мария Петрова',
                    'rating' => 5,
                    'content' => 'Very excited about this! Learning Estonian while enjoying coffee sounds perfect.',
                    'approved' => 1,
                ],
                [
                    'name' => 'Jaak Tamm',
                    'rating' => 4,
                    'content' => 'Suurepärane idee! Kas teil on juba kindel asukoht?',
                    'approved' => 1,
                ],
                [
                    'name' => 'Elena Sokolova',
                    'rating' => 5,
                    'content' => 'Именно то, что нужно иммигрантам! Буду следить за новостями.',
                    'approved' => 1,
                ],
            ],
            
            // Пост 2 - Native Speaker Lessons
            2 => [
                [
                    'name' => 'Liisa Mänd',
                    'rating' => 5,
                    'content' => 'Как носитель языка могу сказать - это лучший способ изучения! Желаю успехов проекту.',
                    'approved' => 1,
                ],
                [
                    'name' => 'Dmitri Volkov',
                    'rating' => 4,
                    'content' => 'Интересный подход к изучению языка. Какая будет стоимость занятий?',
                    'approved' => 1,
                ],
                [
                    'name' => 'Kate Williams',
                    'rating' => 5,
                    'content' => 'This is exactly what I was looking for! I hope you offer online lessons too.',
                    'approved' => 1,
                ],
                [
                    'name' => 'Andres Kukk',
                    'rating' => 4,
                    'content' => 'Väga hea algatus! Kas kavatsete pakkuda ka grupitunde?',
                    'approved' => 0,
                ],
                [
                    'name' => 'Olga Ivanova',
                    'rating' => 5,
                    'content' => 'Записываюсь в очередь! Мечтаю свободно говорить по-эстонски.',
                    'approved' => 1,
                ],
            ],
            
            // Пост 3 - Friday Conversation Club
            3 => [
                [
                    'name' => 'Peeter Saar',
                    'rating' => 5,
                    'content' => 'Отличная инициатива! По пятницам как раз свободен. Где будут проходить встречи?',
                    'approved' => 1,
                ],
                [
                    'name' => 'Sarah Johnson',
                    'rating' => 4,
                    'content' => 'Great idea! I need more practice speaking Estonian. Count me in!',
                    'approved' => 1,
                ],
                [
                    'name' => 'Kristina Rebane',
                    'rating' => 5,
                    'content' => 'Lõpuks ometi! Sellisest võimalusest olen juba ammu unistanud.',
                    'approved' => 1,
                ],
                [
                    'name' => 'Алексей Смирнов',
                    'rating' => 4,
                    'content' => 'Хорошая идея для практики. Надеюсь, будет дружественная атмосфера для новичков.',
                    'approved' => 1,
                ],
            ],
            
            // Пост 4
            4 => [
                [
                    'name' => 'Maarika Kask',
                    'rating' => 5,
                    'content' => 'Väga huvitav projekt! Kindlasti tulen proovima.',
                    'approved' => 1,
                ],
                [
                    'name' => 'Иван Козлов',
                    'rating' => 4,
                    'content' => 'Звучит интересно! А есть ли возможность онлайн участия?',
                    'approved' => 1,
                ],
                [
                    'name' => 'Emma Thompson',
                    'rating' => 5,
                    'content' => 'This looks amazing! Can\'t wait to join your community.',
                    'approved' => 1,
                ],
                [
                    'name' => 'Mart Teder',
                    'rating' => 3,
                    'content' => 'Hea idee, aga kas hinnad on taskukohased?',
                    'approved' => 0,
                ],
            ],
            
            // Пост 5
            5 => [
                [
                    'name' => 'Tatjana Kovalenko',
                    'rating' => 5,
                    'content' => 'Exactly what the Estonian learning community needs! Thank you for this initiative.',
                    'approved' => 1,
                ],
                [
                    'name' => 'Risto Tamm',
                    'rating' => 4,
                    'content' => 'Suurepärane mõte! Millal algatakse esimesed tegevused?',
                    'approved' => 1,
                ],
                [
                    'name' => 'Наталья Белова',
                    'rating' => 5,
                    'content' => 'Давно ищу что-то подобное! Где можно получить больше информации?',
                    'approved' => 1,
                ],
                [
                    'name' => 'John Miller',
                    'rating' => 4,
                    'content' => 'Great concept! I hope there will be beginner-friendly options.',
                    'approved' => 1,
                ],
                [
                    'name' => 'Kaire Mets',
                    'rating' => 5,
                    'content' => 'Lõpuks keegi mõtles sellele! Ootan põnevusega avamist.',
                    'approved' => 1,
                ],
            ],
        ];
        
        // Создаем комментарии для каждого поста
        foreach ($posts as $post) {
            if (isset($commentsData[$post->id])) {
                foreach ($commentsData[$post->id] as $commentData) {
                    Comment::create([
                        'post_id' => $post->id,
                        'name' => $commentData['name'],
                        'rating' => $commentData['rating'],
                        'content' => $commentData['content'],
                        'approved' => $commentData['approved'],
                    ]);
                }
            }
        }
    }
}