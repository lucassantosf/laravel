<?php

namespace App\Services\Contracts;

use Illuminate\Http\Request;

interface AppointmentServiceInterface
{
    public function index();
    public function store(Request $request);
    public function update(Request $request, int $id);
    public function cancel(int $id);
    public function rules(): array;
    public function rules_update(int $id): array;
}
