<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Contato;

class DashboardControlador extends Controller
{
    public function index()
    {   
        $conts = count(Contato::all());
        $first =  Contato::orderBy('id')->first();
        $last = Contato::orderBy('id','desc')->first();
        return view('dashboard',compact('conts','first','last'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
