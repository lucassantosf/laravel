<?php

namespace App\Services\Contracts;

use Illuminate\Http\Request;

interface UserServiceInterface
{
    public function index(Request $request);
    public function store(Request $request);
    public function show(Request $request, int $id);
    public function update(Request $request, int $id);
    public function destroy(int $id);
    public function rules(int $id = null): array;
}
