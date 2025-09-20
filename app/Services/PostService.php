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

    public function getAll() {
        return $this->post->list();
    }

    public function listPaginated() {
        return $this->post->listPaginated();
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