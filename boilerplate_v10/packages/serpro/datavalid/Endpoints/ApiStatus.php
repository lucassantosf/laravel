<?php

namespace Serpro\Datavalid\Endpoints;

use Serpro\Datavalid\Routes;
use Serpro\Datavalid\Endpoints\Endpoint;

class ApiStatus extends Endpoint
{  
    public function is_on()
    { 
        return $this->client->request(
            self::GET,
            Routes::status()->base()
        );
    } 
}