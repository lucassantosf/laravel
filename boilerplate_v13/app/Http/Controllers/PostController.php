<?php

namespace App\Http\Controllers;

use App\Services\Contracts\PostServiceInterface;

class PostController extends Controller
{
    public function __construct(PostServiceInterface $service)
    {
        parent::__construct($service);
    }
}
