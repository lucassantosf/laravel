<?php

namespace App\Services;

use App\Repositories\Contracts\BaseRepositoryInterface;
use App\Services\Contracts\BaseServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class AbstractService implements BaseServiceInterface
{
    public function __construct(
        protected BaseRepositoryInterface $repository
    ) {}

    public function index(Request $request): Collection|LengthAwarePaginator
    {
        return $request->has('paginate') 
            ? $this->repository->paginate($request->get('paginate', 15)) 
            : $this->repository->all();
    }

    public function store(Request $request): Model
    {
        return $this->repository->create($request->all());
    }

    public function show(int $id): Model
    {
        return $this->repository->findOrFail($id);
    }

    public function update(Request $request, int $id): Model
    {
        $this->repository->update($id, $request->all());
        return $this->repository->findOrFail($id);
    }

    public function destroy(int $id): bool
    {
        return $this->repository->delete($id);
    }

    abstract public function rules(?int $id = null): array;

    abstract public function rules_update(?int $int = null): array;
}
