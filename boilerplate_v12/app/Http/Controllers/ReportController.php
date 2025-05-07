<?php

namespace App\Http\Controllers;

use App\Services\PostExportService;
use Illuminate\Http\Request; 

class ReportController extends Controller
{
    protected $exportService;

    public function __construct(PostExportService $exportService)
    {
        $this->exportService = $exportService;
    }

    public function example(Request $request)
    {
        try {
            return $this->exportService->exportExample($request);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(), 
                'success' => false
            ], 500);
        }
    }
}