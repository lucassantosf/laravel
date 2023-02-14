<?php

namespace App\Http\Controllers;

use App\Models\User; 
use Illuminate\Http\Request; 
use Serpro\Datavalid\Client as DataValid;

class SerproController extends Controller
{
    public function validate_document(Request $request)
    {    
        $this->validate($request, ['document'=>'required','nome_da_mae'=>'required','nome_cpf'=>'required','data_nascimento'=>'required|date_format:Y-m-d']);

        try {
            $payload = [
                'key'=> [
                    'cpf'=>$request->document
                ], 
                'answer'=> [
                    "nome"=>$request->nome_cpf,
                    "data_nascimento"=>$request->data_nascimento,
                    "filiacao"=>[
                        "nome_mae"=>$request->nome_da_mae
                    ]
                ]
            ];
            $data_valid = new DataValid();
            $response   = $data_valid->document()->validate_cpf($payload);
            return response()->json($response,200); 
        } catch (\Throwable $th) {
            return response()->json(['message'=>$th->getMessage(),'success'=>false],500);
        }
    }
}