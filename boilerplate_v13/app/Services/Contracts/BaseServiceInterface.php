<?php

namespace App\Services\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

interface BaseServiceInterface
{
    public function index(Request $request): Collection|LengthAwarePaginator;

    public function store(Request $request): Model;

    public function show(int $id): Model;

    public function update(Request $request, int $id): Model;

    public function destroy(int $id): bool;

    public function rules(?int $id = null): array;

    public function rules_update(?int $id = null): array;
}
