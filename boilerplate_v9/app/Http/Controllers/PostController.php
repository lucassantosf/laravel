<?php

namespace App\Http\Controllers;

use App\Models\Post; 
use App\Jobs\FirstJob;
use Illuminate\Http\Request; 

class PostController extends Controller
{
    public function __construct()
    {
        $this->class = Post::class;   
    } 

    public function job(Request $request, int $id)
    {
        try {
            $resource = Post::findorFail($id);
            dispatch(new FirstJob($resource));

            return response()->json(['success'=>true], 200); 
        } catch (\Throwable $th) {
            return response()->json(['message'=>$th->getMessage(),'success'=>false], 500); 
        } 
    }
}