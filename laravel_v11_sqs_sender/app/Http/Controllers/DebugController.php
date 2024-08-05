<?php

namespace App\Http\Controllers;

use App\Exports\ExportPost;
use App\Jobs\SqsJobExample;
use Illuminate\Http\Request; 
use Aws\Sqs\SqsClient;
use Aws\Sns\SnsClient;
use Aws\Exception\AwsException;
use Illuminate\Support\Facades\Log;

use App\Events\ExampleEvent;
use App\Events\PedidoStatusChangedEvent;

class DebugController extends Controller
{ 
    public function event(Request $request)
    {
        try {
            $data = [
                'pedido'=>[
                    'id'=>4575782,
                    'status'=>'GERADO',
                    'produto_id'=>'1',
                    'produto_variacao_id'=>'1',
                ]
            ];
            event(new ExampleEvent($data));
            return response()->json(['message'=>'Success event sented to SQS','success'=>true], 200);  
        } catch (\Throwable $th) {
            return response()->json(['message'=>$th->getMessage(),'success'=>false], 500);  
        } 
    }

    public function event_2(Request $request)
    {
        try {
            $data = [
                'pedido'=>[
                    'id'=>4575782,
                    'status'=>'UTILIZADO',
                    'produto_id'=>'1',
                    'produto_variacao_id'=>'1',
                ]
            ];
            event(new PedidoStatusChangedEvent($data));
            return response()->json(['message'=>'Success event sented to SQS','success'=>true], 200);  
        } catch (\Throwable $th) {
            return response()->json(['message'=>$th->getMessage(),'success'=>false], 500);  
        } 
    }

}