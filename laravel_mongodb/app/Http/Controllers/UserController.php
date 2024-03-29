<?php

namespace App\Http\Controllers;

use App\Models\User; 
use Illuminate\Http\Request; 

class UserController extends Controller
{
    public function __construct()
    {
        $this->class = User::class;   
    } 

    public function show(Request $request, $id)
    {
        try {
            return $this->class::show($request,$id);
        } catch (\Throwable $th) {
            return response()->json(['message'=>$th->getMessage(),'success'=>false], 500); 
        } 
    }

}