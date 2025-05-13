<?php

namespace App\Http\Controllers;

use App\Services\Contracts\AppointmentServiceInterface;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function __construct(AppointmentServiceInterface $service)
    {
        parent::__construct($service);
    }

    public function search(Request $request)
    {
        try {
            $search = $request->query('search');    
            if (!$search) {
                return response()->json(['message' => 'Campo de pesquisa não informado.'], 400);
            }

            $resource = $this->service->search($search);
            return response()->json(['data'=>$resource,'success'=>true], 200); 
        } catch (\Throwable $th) {
            return response()->json(['message'=>$th->getMessage(),'success'=>false], 500); 
        } 
    } 

    public function cancel(Request $request)
    {
        try {
            $this->service->cancel($request->id);
            return response()->json(['success'=>true], 200); 
        } catch (\Throwable $th) {
            return response()->json(['message'=>$th->getMessage(),'success'=>false], 500); 
        } 
    } 

}