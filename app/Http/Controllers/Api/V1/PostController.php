<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\Traits\HandlesLocale;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\PostStatResource;

class PostController extends Controller
{
    use HandlesLocale;

    protected $post;

    public function __construct(PostService $post)
    {
        $this->post = $post;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $locale = $this->setAndGetLocale($request);
            $posts = PostResource::collection($this->post->getAll($locale, $request));
            $paginatedData = PaginateResource::make($posts, PostResource::class);
            return response()->json([
                'posts' => $paginatedData,
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch posts', 'message' => $e->getMessage()], 500);
        }
    }

    public function stats(Request $request)
    {
        try {
            $this->setAndGetLocale($request);
            $stats = new PostStatResource($this->post->getStat());
            return response()->json([
                'stats' => $stats,
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch stats for posts', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}
