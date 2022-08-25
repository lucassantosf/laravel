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
            $return = $this->class::query();
            foreach ($request->query() as $key=>$value)
            {
                $return->where($key,$value);
            }
            return $return->orderby('id','desc')->paginate(10); 
        } catch (\Throwable $th) {
            return response()->json(['message'=>$th->getMessage(),'success'=>false], 500); 
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->class::arrValidation());

        try {
            $resource = $this->class::create($request->all());
            $resource->assignRole($request->role); 

            $resource->password = $request->password;
            $resource->save();
            
            return response()->json($resource, 200); 
        } catch (\Throwable $th) {
            return response()->json(['message'=>$th->getMessage(),'success'=>false], 500); 
        } 
    }

    public function show(Request $request, int $id)
    {
        try {
            $resource = $this->class::findorFail($id);

            return response()->json($resource, 200); 
        } catch (\Throwable $th) {
            return response()->json(['message'=>$th->getMessage(),'success'=>false], 500); 
        } 
    }

    public function update(Request $request, int $id)
    {
        $this->validate($request, $this->class::arrUpdateValidation($id)); 

        try {
            $resource = $this->class::findorFail($id); 

            $resource->fill($request->all());
            $resource->save();

            return response()->json($resource, 200);
        } catch (\Throwable $th) {
            return response()->json(['message'=>$th->getMessage(),'success'=>false], 500); 
        } 
    }

    public function destroy(Request $request, int $id)
    {
        try {

            $destroyed = $this->class::destroy($id);

            if($destroyed === 0)
                return response()->json('Resource does not exist', 404);

            return response()->json('Destroyed', 200);

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
