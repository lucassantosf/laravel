<?php

namespace App\Http\Controllers;

use App\Exports\ExportPost;
use Illuminate\Http\Request; 
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function example(Request $request)
    {  
        try {
            return Excel::download(new ExportPost(),"Post_example.csv")->deleteFileAfterSend(false); 
        } catch (\Throwable $th) {
            return response()->json(['message'=>$th->getMessage(),'success'=>false], 500);  
        } 
    }
}