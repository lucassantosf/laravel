<?php

namespace Serpro\Datavalid\Endpoints;

use Serpro\Datavalid\Routes;
use Serpro\Datavalid\Endpoints\Endpoint;

class Document extends Endpoint
{  
    public function validate_cpf(array $payload)
    { 
        return $this->client->request(
            self::POST,
            Routes::document()->base(),
            ['json' => $payload]
        );
    } 
}