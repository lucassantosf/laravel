<?php

namespace App\Http\Controllers;

use App\Models\Role; 
use Illuminate\Http\Request; 

class RoleController extends Controller
{
    public function __construct()
    {
        $this->class = Role::class;   
    } 

    public function index(Request $request)
    {
        try { 
            return $this->class::all();
        } catch (\Throwable $th) {
            return response()->json(['message'=>$th->getMessage(),'success'=>false], 500); 
        }
    }

}