<?php

namespace App\Repositories;

use App\Models\Appointment;
use App\Repositories\Contracts\AppointmentRepositoryInterface;
use Carbon\Carbon;

class AppointmentRepository implements AppointmentRepositoryInterface
{
    public function all()
    {
        $query = Appointment::query();

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

        $query = $query->where('datetime','>',Carbon::now());

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

    public function update(array $data,int $id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->fill($data)->save();
        return $appointment;
    }

    public function destroy(int $id)
    {
        $post = Appointment::findOrFail($id);
        $post->delete();

        return ['destroyed' => true];
    }
}