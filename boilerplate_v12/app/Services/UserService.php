<?php

namespace App\Services;

use App\Services\Contracts\UserServiceInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;  

class UserService implements UserServiceInterface
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(Request $request)
    {
        return $this->userRepository->all($request);
    }

    public function store(Request $request)
    {
        $user = $this->userRepository->create($request->all());

        $user->assignRole($request->role); 

        return $user;
    }

    public function show(Request $request, int $id)
    {
        return $this->userRepository->find($id);
    }

    public function update(Request $request, int $id)
    {
        return $this->userRepository->update($id, $request->all());
    }

    public function destroy(int $id)
    {
        return $this->userRepository->delete($id);
    }

    public function rules(int $id = null): array
    {
        return [
            'name' => 'required|string',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:'.implode(',',Role::all()->pluck('name')->toArray()),
            "document" => "string|unique:users,document",
        ];
    }

    public function rules_update(int $id = null): array
    {
        return [
            "name" => "string|max:255",
            "email" => "string|email|max:255|unique:users,email,".$id.",id",
            "document" => "string|unique:users,document,$id",
            "password" => "string|min:8|confirmed",
            'role' => 'in:'.implode(',',Role::all()->pluck('name')->toArray()),
            'phone_number' => 'string|max:13',
        ];
    } 
}
