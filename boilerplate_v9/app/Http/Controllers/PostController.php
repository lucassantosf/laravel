<?php

namespace App\Http\Controllers;

use App\Models\Post; 
use Illuminate\Http\Request; 

class PostController extends Controller
{
    public function __construct()
    {
        $this->class = Post::class;   
    } 

}