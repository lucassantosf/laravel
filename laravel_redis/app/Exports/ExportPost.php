<?php

namespace App\Exports;

use App\Models\Post;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;  

class ExportPost implements FromView
{
    protected $id;

    public function __construct($id = null){ 
        $this->id = $id; 
    }

    public function view(): View
    {   
        $data = Post::all();

        /**
         * Conditions to generate report
         */

        return view("exports/example", [ 'data' => $data]); 
    } 
}