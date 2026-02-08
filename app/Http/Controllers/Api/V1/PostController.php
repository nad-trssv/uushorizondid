<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Services\PostService;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\Traits\HandlesLocale;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\PostStatResource;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Str;
use App\Models\Post;

class PostController extends Controller
{
    use HandlesLocale;

    protected $post;

    public function __construct(PostService $post) { $this->post = $post; }

    public function index(Request $request)
    {
        try {
            $this->setAndGetLocale($request);
            $posts = PostResource::collection($this->post->getAll($request));
            $paginatedData = PaginateResource::make($posts, PostResource::class);
            return response()->json(['posts' => $paginatedData], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch posts', 'message' => $e->getMessage()], 500);
        }
    }

    public function stats(Request $request)
    {
        try {
            $this->setAndGetLocale($request);
            $stats = new PostStatResource($this->post->getStat());
            return response()->json(['stats' => $stats], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch stats for posts', 'message' => $e->getMessage()], 500);
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

    public function store(StorePostRequest $request)
    {
        try {
            $created = $this->post->create($request->validated());
            return response()->json(['post' => new PostResource($created), 'message' => 'Post created successfully'], 201);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to create post', 'message' => $e->getMessage()], 500);
        }
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        try {
            $updated = $this->post->update($post->id, $request->validated());
            return response()->json(['post' => new PostResource($updated), 'message' => 'Post updated successfully'], 200);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to update post', 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy(Post $post)
    {
        try {
            $this->post->deletePost($post->id);
            return response()->json(['message' => 'Post deleted successfully'], 200);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to delete post', 'message' => $e->getMessage()], 500);
        }
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => [
                'required',
                File::image()->types(['jpg','jpeg','png','webp','avif','heic','svg'])->max(5 * 1024),
            ],
        ]);

        $dir = 'posts';
        $ext = $request->file('file')->extension();
        $filename = Str::uuid().'.'.$ext;
        $path = $request->file('file')->storeAs($dir, $filename, 'public');

        return response()->json([
            'path' => $path,
            'url'  => asset('storage/'.$path),
            'message' => 'Image uploaded successfully',
        ], 201);
    }
}
