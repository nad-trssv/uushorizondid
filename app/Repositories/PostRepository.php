<?php

namespace App\Repositories;

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
    public function listPaginated()
    {
        return $this->model->paginate(3);
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
