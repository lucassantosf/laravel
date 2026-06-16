<?php

namespace App\Exports;

use App\Models\Post;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportPost
{
    protected $id;

    public function __construct($id = null)
    {
        $this->id = $id;
    }

    public function download(): StreamedResponse
    {
        $posts = Post::with('user')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="posts.csv"',
        ];

        return new StreamedResponse(function () use ($posts) {
            $file = fopen('php://output', 'w');

            // CSV Header
            fputcsv($file, ['ID', 'Autor', 'Título', 'Conteúdo', 'Data Criação']);

            // CSV Data
            foreach ($posts as $post) {
                fputcsv($file, [
                    $post->id,
                    $post->user->name ?? 'N/A',
                    $post->title,
                    $post->content,
                    $post->created_at->format('d/m/Y H:i:s'),
                ]);
            }

            fclose($file);
        }, 200, $headers);
    }
}