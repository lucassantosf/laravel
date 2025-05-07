<?php

namespace App\Services\Contracts;

use Illuminate\Http\Request;

interface AppointmentServiceInterface
{
    public function index(Request $request);
    public function store(Request $request);
    public function show(Request $request, int $id);
    public function cancel(int $id);
    public function rules(int $id = null): array;
}
