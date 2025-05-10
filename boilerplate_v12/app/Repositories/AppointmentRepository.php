<?php

namespace App\Repositories;

use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Repositories\Contracts\AppointmentRepositoryInterface;

class AppointmentRepository implements AppointmentRepositoryInterface
{
    public function all(Request $request)
    {
        $query = Appointment::query();

        foreach ($request->query() as $key => $value) {
            $query->where($key, $value);
        }

        return $query->orderBy('id', 'desc')->paginate(10);
    }

    public function search(string $search)
    {
        $query = Appointment::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('document', 'like', "%$search%");
            });
        }

        return $query->first();
    }

    public function find(int $id)
    {
        return Appointment::findOrFail($id);
    }

    public function create(array $data)
    {
        return Appointment::create($data);
    }

    public function destroy(int $id)
    {
        $post = Appointment::findOrFail($id);
        $post->delete();

        return ['destroyed' => true];
    }
}