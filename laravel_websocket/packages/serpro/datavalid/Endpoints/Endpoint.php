<?php

namespace Serpro\Datavalid\Endpoints;

use Serpro\Datavalid\Client;

abstract class Endpoint
{
    /**
     * @var string
     */
    const GET = 'GET';
    /**
     * @var string
     */
    const POST = 'POST';    
    /**
     * @var string
     */
    const PUT = 'PUT';
    /**
     * @var string
     */
    const DELETE = 'DELETE';
    /**
     * @var string
     */
    const PATCH = 'PATCH';

    /**
     * @var \Acg\Client
     */
    protected $client;

    /**
     * @param \Acg\Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }
}