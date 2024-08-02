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

class DebugController extends Controller
{
    public function sqs(Request $request)
    {  
        try {
            dispatch(new SqsJobExample());
            return response()->json(['message'=>'Success dispatched to SQS','success'=>true], 200);  
        } catch (\Throwable $th) {
            return response()->json(['message'=>$th->getMessage(),'success'=>false], 500);  
        } 
    }

    public function sns(Request $request)
    {  
        try {
            $snsClient = new SnsClient([
                'version' => 'latest',
                'region' => env('AWS_DEFAULT_REGION'),
                'credentials' => [
                    'key' => env('AWS_ACCESS_KEY_ID'),
                    'secret' => env('AWS_SECRET_ACCESS_KEY'),
                ],
            ]);
    
            $topicArn = 'arn:aws:sns:us-east-1:340682254400:LucasSNSTests2';
    
            $message = [
                "job" => "App\\Jobs\\SqsJobExample",
                "data" => [
                    "payload" => [
                        'key1' => 'value1',
                        'key2' => 'value2',
                    ]
                ]
            ];
    
            $params = [
                'Message' => json_encode($message),
                'TopicArn' => $topicArn,
            ];
            $params['Subject'] = 'Subject';
    
            $result = $snsClient->publish($params);
    
            Log::info('SNS publish result:', $result->toArray());
    
            return response()->json(['message' => 'Success communication to SNS', 'success' => true], 200);
        } catch (AwsException $th) {
            Log::error('SNS publish error: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage(), 'success' => false], 500);
        } 
    }

    public function event(Request $request)
    {
        try {
            $data = [
                'key1'=>'key1'
            ];
            event(new ExampleEvent($data));
            return response()->json(['message'=>'Success event sented to SQS','success'=>true], 200);  
        } catch (\Throwable $th) {
            return response()->json(['message'=>$th->getMessage(),'success'=>false], 500);  
        } 
    }

}