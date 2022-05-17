<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Contato;

class ContatoController extends Controller
{
    public function index(){
        $conts = DB::table('contatos')->orderBy('nome')->get();
        return view('index',compact('conts'));
    }

    public function contatoJson($id){
        $cont = Contato::find($id);
        if(isset($id)){
            return $cont->toJson();
        }else{
            return redirect('/');
        }
    }

    public function create()
    {
        return view('forms/formContato');        
    }

    public function store(Request $request)
    {   
        //validacao de campos com 'msgs' personalizadas
        $regras = [
            'nome'=>'required|min:5|max:50',
            'email'=>'required|email',
            'idade'=>'required|max:3',        
            'telefone'=>'required|min:14',
        ];
        $mensagens = [
            'required'=>'O campo :attribute não pode ser vazio',            
            'nome.min'=>'É necessário no minimo 5 caracteres no campo nome',
            'telefone.min'=>'Campo de telefone incompátivel',
            'idade.max'=>'Idade não pode ter mais que 3 caracteres',
            'email.email'=>'Formato do email não é válido'
        ];
        $request->validate($regras,$mensagens);
            
        $cont = new Contato();
        $cont->nome = $request->input('nome');
        $cont->email = $request->input('email');
        $cont->idade = $request->input('idade');
        $cont->telefone = $request->input('telefone');
        $cont->save();
        return redirect('/');
    }

    public function listar(){
        $conts = Contato::all();
        return view('listarContatos',compact('conts'));
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $contact = Contato::find($id);
        if(isset($contact)){
            return view('forms.formContatoEdit',compact('contact'));
        }
    }

    public function update(Request $request, $id)
    {
        //validacao de campos com 'msgs' personalizadas
        $regras = [
            'nome'=>'required|min:5|max:50',
            'email'=>'required|email',
            'idade'=>'required|max:3',        
            'telefone'=>'required|min:14',
        ];
        $mensagens = [
            'required'=>'O campo :attribute não pode ser vazio',            
            'nome.min'=>'É necessário no minimo 5 caracteres no campo nome',
            'telefone.min'=>'Campo de telefone incompátivel',
            'idade.max'=>'Idade não pode ter mais que 3 caracteres',
            'email.email'=>'Formato do email não é válido'
        ];
        $request->validate($regras,$mensagens);        
        
        $cont = Contato::find($id);

        if(isset($cont)){
            $cont->nome = $request->input('nome');
            $cont->email = $request->input('email');
            $cont->idade = $request->input('idade');
            $cont->telefone = $request->input('telefone');
            $cont->save();
        }        
        return redirect('/');
    }

    public function destroy($id)
    {
        $cont = Contato::find($id);
        if(isset($cont)){
            $cont->delete();
        }
        return redirect('/contato');
    }
}
