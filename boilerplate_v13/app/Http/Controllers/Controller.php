<?php

namespace App\Http\Controllers;

use App\Services\Contracts\BaseServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

abstract class Controller
{
    public function __construct(
        protected BaseServiceInterface $service
    ) {}

    public function index(Request $request): JsonResponse
    {
        try {
            return response()->json($this->service->index($request));
        } catch (\Throwable $th) {
            return $this->errorResponse($th);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            return response()->json($this->service->store($request), 201);
        } catch (\Throwable $th) {
            return $this->errorResponse($th);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            return response()->json($this->service->show($id));
        } catch (\Throwable $th) {
            return $this->errorResponse($th);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            return response()->json($this->service->update($request, $id));
        } catch (\Throwable $th) {
            return $this->errorResponse($th);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->service->destroy($id);
            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            return $this->errorResponse($th);
        }
    }

    protected function errorResponse(\Throwable $th): JsonResponse
    {
        $status = 500;
        $message = $th->getMessage();

        if ($th instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
            $status = 404;
            $message = 'Resource not found';
        }

        return response()->json([
            'success' => false,
            'message' => $message,
            'trace' => config('app.debug') ? $th->getTrace() : null
        ], $status);
    }

    public static function storeFile($file, $diretorio, $visibilidade = 'private'): string
    {
        $nomeUnico = uniqid(date('HisYmd')) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs($diretorio, $nomeUnico, ['disk' => 'public', 'visibility' => $visibilidade]);
        return basename($path);
    }

    public static function limpaString($string, $allow = ''): string
    {
        return preg_replace('/[^a-zA-Z0-9' . preg_quote($allow, '/') . ']/', '', $string);
    }
}
