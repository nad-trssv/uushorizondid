<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Services\PostService;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\Traits\HandlesFrontLocale as HandlesLocale;
use App\Models\Post;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    use HandlesLocale;

    protected $post;

    public function __construct(PostService $post) { $this->post = $post; }

    public function index(Request $request)
    {
        try {
            $this->setAndGetLocale($request);
            $request->merge(['per_page' => 12]);
            $posts = $this->post->getPublished($request); 
    
            return view('main.blog.index', compact('posts'));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch posts', 'message' => $e->getMessage()], 500);
        }
    }

    public function show(Request $request)
    {
        $this->setAndGetLocale($request);
        $slug = (string) $request->route('slug');
        if (!Post::where('slug', $slug)->exists()) {
            return redirect('/')->with('error', 'Мероприятие не найдено.');
        }
        
        $post = Post::where('slug', $slug)
            ->with(['user', 'translations', 'seo.translations', 'comments' => function ($query) {
                $query->where('approved', true);
            }])
            ->where('status', 'published')
            ->firstOrFail();

        // SEO
        $pageTitle = trim(($tr['title'] ?? $post->title).' — '.config('app.name'));
        $metaDesc  = Str::limit(strip_tags($tr['short_description'] ?? $tr['full_description'] ?? ''), 160);

        return view('main.blog.show', compact('post', 'pageTitle', 'metaDesc'));
    }

    public function storeComment(Request $request, $slug)
    {
        $post = Post::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $data = $request->validate([
            'name'    => ['required','string','max:255'],
            'rating'  => ['required','integer','min:1','max:5'], // обязательно!
            'content' => ['required','string','max:5000'],       // обязательно!
        ]);

        $post->comments()->create([
            'name'     => $data['name'],
            'rating'   => $data['rating'],
            'content'  => $data['content'],
            'approved' => true, // или false, если нужна модерация
        ]);

        return back()->with('success', 'Спасибо за отзыв!');
    }

}
