<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $fillable = ['nome','preco'];

    function categorias(){
    	return $this->belongToMany("App\Categoria",'produto_categorias');
    }

}
