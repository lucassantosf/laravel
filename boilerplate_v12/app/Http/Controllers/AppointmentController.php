<?php

namespace App\Http\Controllers;

use App\Models\Post; 
use App\Jobs\FirstJob;
use App\Services\Contracts\AppointmentServiceInterface;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function __construct(AppointmentServiceInterface $service)
    {
        parent::__construct($service);
    }
}