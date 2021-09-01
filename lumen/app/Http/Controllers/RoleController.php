<?php

namespace App\Http\Controllers;
 
use Spatie\Permission\Models\Role;  

class RoleController extends Controller
{
    public function __construct()
    {
        $this->class = Role::class; 
    } 
}