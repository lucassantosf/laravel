<?php

namespace App\Services;

use App\Repositories\Contracts\PostRepositoryInterface;
use App\Services\Contracts\PostServiceInterface;

class PostService extends AbstractService implements PostServiceInterface
{
    public function __construct(PostRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function rules(?int $id = null): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'nullable|string|in:draft,published',
            'user_id' => 'required|exists:users,id'
        ];
    }

    public function rules_update(?int $id = null): array
    {
        return $this->rules($id);
    }
}
