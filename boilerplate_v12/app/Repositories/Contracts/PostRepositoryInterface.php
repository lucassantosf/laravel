<?php

namespace App\Repositories\Contracts;

use Illuminate\Http\Request;

interface PostRepositoryInterface
{
    public function all(Request $request);
    public function find(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
}
