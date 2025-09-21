<?php 
namespace App\Services;

use App\Repositories\PostRepository;

class PostService {
    protected $post;

    public function __construct(PostRepository $post) {
        $this->post = $post;
    }

    public function createPost(array $data) {
        return $this->post->create($data);
    }

    public function getAll($locale, $request): \Illuminate\Pagination\LengthAwarePaginator {
        return $this->post->getAll($locale, $request);
    }

    public function getStat(): array
    {
        $stats = $this->post->getStat();
        return $stats;
    }

    public function getPostById($id) {
        return $this->post->find($id);
    }

    public function updatePost($id, array $data) {
        return $this->post->update($id, $data);
    }
    public function deletePost($id) {
        return $this->post->delete($id);
    }

    
}