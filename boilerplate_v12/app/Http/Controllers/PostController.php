<?php

namespace App\Http\Controllers;

use App\Models\Post; 
use App\Jobs\FirstJob;
use App\Services\Contracts\PostServiceInterface;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct(PostServiceInterface $service)
    {
        parent::__construct($service);
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