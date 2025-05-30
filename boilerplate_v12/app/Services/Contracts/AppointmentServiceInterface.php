<?php

namespace App\Services\Contracts;

interface AppointmentServiceInterface
{
    public function index();
    public function store(array $data);
    public function update(array $data, int $id);
    public function search(string $search);
    public function cancel(int $id);
    public function rules(): array;
    public function rules_update(int $id): array;
}
