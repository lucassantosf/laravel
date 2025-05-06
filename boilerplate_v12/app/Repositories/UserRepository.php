<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Http\Request;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function all(Request $request)
    {
        $query = User::query();

        foreach ($request->query() as $key => $value) {
            $query->where($key, $value);
        }

        return $query->orderBy('id', 'desc')->paginate(10);
    }

    public function find(int $id)
    {
        return User::findOrFail($id);
    }

    public function create(array $data)
    {
        return User::create($data);
    }

    public function update(int $id, array $data)
    {
        $user = User::findOrFail($id);
        $user->fill($data)->save();

        return $user;
    }

    public function delete(int $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return ['destroyed' => true];
    }
}
