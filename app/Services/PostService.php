<?php
namespace App\Services;

use App\Models\Post;
use App\Models\PostTranslation;
use App\Models\PostSeo;
use App\Models\PostSeoTranslation;
use App\Models\Language;
use App\Repositories\PostRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class PostService {
    protected $post;

    public function __construct(PostRepository $post) { $this->post = $post; }

    public function getAll($request): \Illuminate\Pagination\LengthAwarePaginator {
        return $this->post->getAll($request);
    }
    public function getPublished($request): \Illuminate\Pagination\LengthAwarePaginator {
        return $this->post->getPublished($request);
    }

    public function getStat(): array { return $this->post->getStat(); }

    public function getById($id) {
        // Возвращаем пост с зависимостями для ресурса
        return \App\Models\Post::with([
            'translations.language',
            'seo.translations.language',
            'gallery',
            'comments',
            'user.role'
        ])->findOrFail($id);
    }

    public function create(array $data) {
        return DB::transaction(function () use ($data) {
            $defaultLangId = Language::where('is_default', true)->value('id');
            $defaultTranslation = collect($data['translations'] ?? [])->firstWhere('language_id', $defaultLangId);
            $defaultTitle = $defaultTranslation['title'] ?? null;

            // slug авто, если пуст
            if ($data['slug'] === null) {
                $data['slug'] = Str::slug($defaultTitle ?? Str::uuid());
            }
            if (Post::where('slug', $data['slug'])->exists()) {
                $data['slug'] = $data['slug'].'-'.mt_rand(100000, 999999);
            }

            // Set the user_id to the currently authenticated user if not provided
            if (empty($data['user_id']) && auth()->check()) {
                $data['user_id'] = auth()->id();
            }

            $postFields = Arr::only($data, ['slug','status','image','published_at','user_id']);
            $post = Post::create($postFields);

            // translations
            foreach ($data['translations'] ?? [] as $t) {
                PostTranslation::create(array_merge(Arr::only($t, ['language_id','title','description']), [
                    'post_id' => $post->id
                ]));
            }

            // SEO
            if (!empty($data['seo'])) {
                $postSeo = PostSeo::create(['post_id' => $post->id]);
                foreach ($data['seo'] as $s) {
                    PostSeoTranslation::create(array_merge(Arr::only($s, [
                        'language_id','meta_title','meta_description','meta_keywords'
                    ]), ['post_seo_id' => $postSeo->id]));
                }
            }

            return $post->load(['translations','seo.translations','gallery','comments','user.role']);
        });
    }

    public function update(int $id, array $data) {
        return DB::transaction(function () use ($id, $data) {
            $post = Post::findOrFail($id);

            $postFields = Arr::only($data, ['slug','status','image','published_at','user_id']);
            $post->update($postFields);

            // translations upsert
            foreach ($data['translations'] ?? [] as $t) {
                $keys = ['post_id' => $post->id, 'language_id' => $t['language_id']];
                $vals = Arr::only($t, ['title','description']);
                PostTranslation::updateOrCreate($keys, $vals);
            }

            // SEO upsert
            if (!empty($data['seo'])) {
                $postSeo = PostSeo::firstOrCreate(['post_id' => $post->id]);
                foreach ($data['seo'] as $s) {
                    $keys = ['post_seo_id' => $postSeo->id, 'language_id' => $s['language_id']];
                    $vals = Arr::only($s, ['meta_title','meta_description','meta_keywords']);
                    PostSeoTranslation::updateOrCreate($keys, $vals);
                }
            }

            return $post->load(['translations','seo.translations','gallery','comments','user.role']);
        });
    }

    public function deletePost($id) {
        return DB::transaction(function () use ($id) {
            $post = Post::findOrFail($id);
            $post->delete();
            return true;
        });
    }
}
