<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Exports\ExportPost;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PostExportService
{
    public function exportExample(Request $request): StreamedResponse
    {
        // Se quiser aplicar filtros aqui e passar pro ExportPost, pode
        return (new ExportPost())->download();
    }
}