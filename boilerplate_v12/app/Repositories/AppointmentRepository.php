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

    public function find(int $id)
    {
        return Appointment::findOrFail($id);
    }

    public function create(array $data)
    {
        return Appointment::create($data);
    }

    public function destroy(array $data)
    {
        $post = Appointment::findOrFail($id);
        $post->delete();

        return ['destroyed' => true];
    }
}
