<?php

namespace App\Services;

use App\Services\Contracts\PostServiceInterface;
use App\Repositories\Contracts\PostRepositoryInterface;
use Illuminate\Http\Request;

class PostService implements PostServiceInterface
{
    protected $postRepository;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function index(Request $request)
    {
        return $this->postRepository->all($request);
    }

    public function store(Request $request)
    {
        return $this->postRepository->create($request->all());
    }

    public function show(Request $request, int $id)
    {
        return $this->postRepository->find($id);
    }

    public function update(Request $request, int $id)
    {
        return $this->postRepository->update($id, $request->all());
    }

    public function destroy(int $id)
    {
        return $this->postRepository->delete($id);
    }

    public function rules(int $id = null): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string',
            'content' => 'required|string',
            'status' => 'required|boolean'
        ];
    }

    public function rules_update(int $id = null): array
    {
        return [
            'user_id' => 'exists:users,id',
            'title' => 'string',
            'content' => 'string',
            'status' => 'boolean'
        ];
    }
}
