<?php

namespace App\Services;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportPost;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PostExportService
{
    public function exportExample(Request $request): BinaryFileResponse
    {
        // Se quiser aplicar filtros aqui e passar pro ExportPost, pode
        return Excel::download(new ExportPost(), "Post_example.csv")->deleteFileAfterSend(false);
    }
}
