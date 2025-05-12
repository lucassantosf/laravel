<?php

namespace App\Repositories\Contracts;

interface AppointmentRepositoryInterface
{
    public function all();
    public function find(int $data);
    public function search(string $search);
    public function create(array $data);
    public function update(array $data,int $id);
    public function destroy(int $id);
}
