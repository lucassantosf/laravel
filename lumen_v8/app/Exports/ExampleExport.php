<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;  
use DB;

class ExampleExport implements FromView
{
    protected $id;

    public function __construct($id){ 
        $this->id = $id; 
    }

    public function view(): View
    {   
        return view('exports'); 
    } 
}
