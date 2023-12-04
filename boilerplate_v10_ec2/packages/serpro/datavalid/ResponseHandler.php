<?php

namespace Serpro\Datavalid;

use GuzzleHttp\Exception\ClientException; 

class ResponseHandler
{
    /**
     * @param string $payload
     *
     * @return \ArrayObject
     */
    public static function success($payload)
    {
        return json_decode($payload);
    } 
    
}