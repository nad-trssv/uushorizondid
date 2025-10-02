<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Services\PostService;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\Traits\HandlesLocale;
use App\Models\Post;

class BlogController extends Controller
{
    use HandlesLocale;

    protected $post;

    public function __construct(PostService $post) { $this->post = $post; }

    public function index(Request $request)
    {
        try {
            $this->setAndGetLocale($request);
            $request->merge(['per_page' => 12, 'order_by' => 'published_at']);
            $posts = $this->post->getAll($request); 
    
            return view('main.blog', compact('posts'));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch posts', 'message' => $e->getMessage()], 500);
        }
    }

    public function show(Request $request, Post $post)
    {
        try {
            $this->setAndGetLocale($request);
            $post = $this->post->getById($post->id);
            return response()->json(['post' => new PostResource($post)], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch the post', 'message' => $e->getMessage()], 500);
        }
    }
}
