<?php

namespace App\Http\Controllers;

use App\Models\Post; 

class PostController extends Controller
{
    public function __construct()
    {
        $this->class = Post::class;   
    }
}