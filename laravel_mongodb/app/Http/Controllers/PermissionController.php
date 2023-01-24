<?php

namespace App\Http\Controllers;

use App\Models\Permission; 
use Illuminate\Http\Request; 

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->class = Permission::class;   
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