<?php

namespace App\Repositories\Contracts;

use Illuminate\Http\Request;

interface AppointmentRepositoryInterface
{
    public function all(Request $request);
    public function find(int $data);
    public function search(string $search);
    public function create(array $data);
    public function destroy(int $id);
}
