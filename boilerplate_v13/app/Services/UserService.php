<?php

namespace App\Services;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Contracts\UserServiceInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class UserService extends AbstractService implements UserServiceInterface
{
    public function __construct(UserRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function store(Request $request): Model
    {
        $data = $request->all();
        $data['password'] = Hash::make($data['password']);
        $user = $this->repository->create($data);

        // Assign default client role
        $user->assignRole('client');

        return $user;
    }

    public function update(Request $request, int $id): Model
    {
        $data = $request->all();
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        $this->repository->update($id, $data);
        return $this->repository->findOrFail($id);
    }

    public function rules(?int $id = null): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ];
    }

    public function rules_update(?int $id = null): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'sometimes|required|string|min:6|confirmed',
        ];
    }
}
