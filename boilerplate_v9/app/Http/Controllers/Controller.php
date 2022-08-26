<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request; 
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    protected $class;

    public function index(Request $request)
    {
        try { 
            return $this->class::index($request);
        } catch (\Throwable $th) {
            return response()->json(['message'=>$th->getMessage(),'success'=>false], 500); 
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->class::arrValidation());

        try {
            return $this->class::store($request);
        } catch (\Throwable $th) {
            return response()->json(['message'=>$th->getMessage(),'success'=>false], 500); 
        } 
    }

    public function show(Request $request, int $id)
    {
        try {
            return $this->class::show($request,$id);
        } catch (\Throwable $th) {
            return response()->json(['message'=>$th->getMessage(),'success'=>false], 500); 
        } 
    }

    public function update(Request $request, int $id)
    {
        $this->validate($request, $this->class::arrUpdateValidation($id)); 

        try {
            return $this->class::update_one($request,$id);
        } catch (\Throwable $th) {
            return response()->json(['message'=>$th->getMessage(),'success'=>false], 500); 
        } 
    }

    public function destroy(Request $request, int $id)
    {
        try {
            return $this->class::destroy($id);
        } catch (\Throwable $th) {
            return response()->json(['message'=>$th->getMessage(),'success'=>false], 500);  
        } 
    }

    public static function storeFile($file, $diretorio, $visibilidade = 'private'){
        $nome_unico = uniqid(date('HisYmd')) . '.' . $file->getClientOriginalExtension();
        Storage::disk('s3')->put($diretorio . '/' . Controller::limpaString($nome_unico, '.'), file_get_contents($file), $visibilidade); 
        return $nome_unico;
    }
}
