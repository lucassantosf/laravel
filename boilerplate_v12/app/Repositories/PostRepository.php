<?php

namespace App\Repositories;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Repositories\Contracts\PostRepositoryInterface;

class PostRepository implements PostRepositoryInterface
{
    public function all(Request $request)
    {
        $query = Post::query();

        foreach ($request->query() as $key => $value) {
            $query->where($key, $value);
        }

        return $query->orderBy('id', 'desc')->paginate(10);
    }

    public function find(int $id)
    {
        return Post::findOrFail($id);
    }

    public function create(array $data)
    {
        return Post::create($data);
    }

    public function update(int $id, array $data)
    {
        $post = Post::findOrFail($id);
        $post->fill($data)->save();

        return $post;
    }

    public function delete(int $id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return ['destroyed' => true];
    }
}
