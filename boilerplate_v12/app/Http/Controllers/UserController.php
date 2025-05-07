<?php

namespace App\Http\Controllers;

use App\Models\User; 
use Illuminate\Http\Request; 
use App\Services\Contracts\UserServiceInterface;

class UserController extends Controller
{
    public function __construct(UserServiceInterface $service)
    {
        parent::__construct($service);
    }

}