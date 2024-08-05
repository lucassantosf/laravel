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
    
}