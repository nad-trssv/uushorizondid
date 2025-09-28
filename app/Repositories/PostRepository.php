<?php

namespace App\Repositories;

use App\Models\Comment;
use App\Models\Post;

class PostRepository
{
    protected $model;

    public function __construct(Post $post)
    {
        $this->model = $post;
    }

    public function list()
    {
        return $this->model->all();
    }

    public function getAll($request): \Illuminate\Pagination\LengthAwarePaginator
    {
        $query = $this->model->with(['translations', 'gallery', 'comments']);

        // ===== Поиск =====
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'LIKE', "%{$search}%")
                ->orWhereHas('translations', function($translationQuery) use ($search) {
                    $translationQuery->where('title', 'LIKE', "%{$search}%")
                                    ->orWhere('description', 'LIKE', "%{$search}%");
                });
            });
        }

        // ===== Фильтрация по статусу =====
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // ===== Фильтрация по дате =====
        if ($request->has('date') && !empty($request->date)) {
            $query->whereDate('created_at', $request->date);
        }

        // ===== Сортировка =====
        $sortBy = $request->get('sortBy', 'created_at');
        $sortDirection = $request->get('sortDirection', 'desc');
        
        // Разрешенные поля для сортировки
        $allowedSortFields = ['id', 'created_at', 'published_at', 'status'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortDirection);
        } else {
            // По умолчанию сортируем по дате создания
            $query->orderBy('created_at', 'desc');
        }

        // ===== Пагинация =====
        $perPage = $request->input('perPage', 10);
        $page = $request->input('page', 1);

        return $query->paginate($perPage, ['*'], 'page', $page);
    }

    public function getStat($locale = null): array
    {
        $locale = $locale ?? app()->getLocale();
        $stats = [];

        // Базовая статистика
        $stats['total_count'] = Post::count();
        $stats['total_views'] = Post::sum('views');
        $stats['total_comments'] = Comment::count();

        // Статистика по статусам
        $stats['by_status'] = Post::select('status', \DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Самый популярный пост по рейтингу
        $mostRatedPost = Post::withAvg('comments as avg_rating', 'rating')
            ->having('avg_rating', '>', 0)
            ->orderBy('avg_rating', 'desc')
            ->first();
        
        if ($mostRatedPost) {
            $translation = $mostRatedPost->translations->firstWhere('language.code', $locale);
            $stats['most_rated_post'] = [
                'id' => $mostRatedPost->id,
                'title' => $translation->title ?? 'Без заголовка',
                'rating' => round($mostRatedPost->avg_rating, 2),
                'comments_count' => $mostRatedPost->comments()->count()
            ];
        } else {
            $stats['most_rated_post'] = null;
        }

        // Самый популярный пост по просмотрам
        $mostViewedPost = Post::withCount('comments')
            ->orderBy('views', 'desc')
            ->first();
        
        if ($mostViewedPost) {
            $translation = $mostViewedPost->translations->firstWhere('language.code', $locale);
            $stats['most_viewed_post'] = [
                'id' => $mostViewedPost->id,
                'title' => $translation->title ?? 'Без заголовка',
                'views' => $mostViewedPost->views,
                'comments_count' => $mostViewedPost->comments_count
            ];
        } else {
            $stats['most_viewed_post'] = null;
        }

        // Самый комментируемый пост
        $mostCommentedPost = Post::withCount('comments')
            ->orderBy('comments_count', 'desc')
            ->first();
        
        if ($mostCommentedPost) {
            $translation = $mostCommentedPost->translations->firstWhere('language.code', $locale);
            $stats['most_commented_post'] = [
                'id' => $mostCommentedPost->id,
                'title' => $translation->title ?? 'Без заголовка',
                'comments_count' => $mostCommentedPost->comments_count
            ];
        } else {
            $stats['most_commented_post'] = null;
        }

        // Посты с галереей
        $stats['with_gallery'] = Post::has('gallery')->count();
        
        // Посты с изображениями
        $stats['with_images'] = Post::whereNotNull('image')
            ->where('image', '!=', '')
            ->count();

        // Средний рейтинг всех постов
        $avgRating = Comment::avg('rating');
        $stats['average_rating'] = $avgRating ? round($avgRating, 2) : 0;

        // Сравнение с предыдущим месяцем
        $currentMonth = now();
        $previousMonth = now()->subMonth();
        
        $currentMonthPosts = Post::whereYear('created_at', $currentMonth->year)
            ->whereMonth('created_at', $currentMonth->month)
            ->count();
        
        $previousMonthPosts = Post::whereYear('created_at', $previousMonth->year)
            ->whereMonth('created_at', $previousMonth->month)
            ->count();
        
        $stats['monthly_comparison'] = [
            'current' => $currentMonthPosts,
            'previous' => $previousMonthPosts,
            'difference' => $currentMonthPosts - $previousMonthPosts,
            'percentage' => $previousMonthPosts > 0 ? 
                round(($currentMonthPosts - $previousMonthPosts) / $previousMonthPosts * 100, 2) : 
                ($currentMonthPosts > 0 ? 100 : 0)
        ];

        // Сравнение с предыдущим годом
        $currentYear = now();
        $previousYear = now()->subYear();
        
        $currentYearPosts = Post::whereYear('created_at', $currentYear->year)->count();
        $previousYearPosts = Post::whereYear('created_at', $previousYear->year)->count();
        
        $stats['yearly_comparison'] = [
            'current' => $currentYearPosts,
            'previous' => $previousYearPosts,
            'difference' => $currentYearPosts - $previousYearPosts,
            'percentage' => $previousYearPosts > 0 ? 
                round(($currentYearPosts - $previousYearPosts) / $previousYearPosts * 100, 2) : 
                ($currentYearPosts > 0 ? 100 : 0)
        ];

        // Статистика просмотров по месяцам
        $monthlyViews = Post::select(
                \DB::raw('YEAR(created_at) as year'),
                \DB::raw('MONTH(created_at) as month'),
                \DB::raw('SUM(views) as total_views')
            )
            ->where('created_at', '>=', now()->subYear())
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();
        
        $stats['monthly_views'] = $monthlyViews;

        // Статистика комментариев по месяцам
        $monthlyComments = Comment::select(
                \DB::raw('YEAR(created_at) as year'),
                \DB::raw('MONTH(created_at) as month'),
                \DB::raw('COUNT(*) as total_comments')
            )
            ->where('created_at', '>=', now()->subYear())
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();
        
        $stats['monthly_comments'] = $monthlyComments;

        // Распределение по датам (последние 30 дней)
        $recentPosts = Post::where('published_at', '>=', now()->subDays(30))
            ->count();
        $stats['recent_posts'] = $recentPosts;

        // Активность постов (посты с комментариями за последние 7 дней)
        $recentComments = Comment::where('created_at', '>=', now()->subDays(7))
            ->distinct('post_id')
            ->count('post_id');
        $stats['recent_active_posts'] = $recentComments;

        return $stats;
    }

    public function find($id)
    {
        return $this->model->find($id);
    }
    public function create(array $data)
    {
        return $this->model->create($data);
    }
    public function update($id, array $data)
    {
        $post = $this->find($id);
        if ($post) {
            $post->update($data);
            return $post;
        }
        return null;
    }
    public function delete($id)
    {
        $post = $this->find($id);
        if ($post) {
            $post->delete();
            return true;
        }
        return false;
    }
    

}
