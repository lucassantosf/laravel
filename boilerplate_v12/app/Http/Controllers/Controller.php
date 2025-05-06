<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected $service;

    public function __construct($serviceInterface)
    {
        $this->service = $serviceInterface;
    }

    public function index(Request $request)
    {
        try {
            return response()->json($this->service->index($request));
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'success' => false], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->service->rules());

        if ($validator->fails())
            return response()->json($validator->messages(), 422);

        try {
            return response()->json($this->service->store($request));
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'success' => false], 500);
        }
    }

    public function show(Request $request, int $id)
    {
        try {
            return response()->json($this->service->show($request, $id));
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'success' => false], 500);
        }
    }

    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), $this->service->rules($id));

        if ($validator->fails())
            return response()->json($validator->messages(), 422);

        try {
            return response()->json($this->service->update($request, $id));
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'success' => false], 500);
        }
    }

    public function destroy(Request $request, int $id)
    {
        try {
            return response()->json($this->service->destroy($id));
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'success' => false], 500);
        }
    }

    public static function storeFile($file, $diretorio, $visibilidade = 'private')
    {
        $nome_unico = uniqid(date('HisYmd')) . '.' . $file->getClientOriginalExtension();
        Storage::disk('s3')->put(
            $diretorio . '/' . Controller::limpaString($nome_unico, '.'),
            file_get_contents($file),
            $visibilidade
        );
        return $nome_unico;
    }

    // Presumo que essa função esteja declarada em outro lugar.
    public static function limpaString($string, $allow = '')
    {
        return preg_replace('/[^a-zA-Z0-9' . preg_quote($allow, '/') . ']/', '', $string);
    }
}
