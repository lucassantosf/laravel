<?php

namespace App\Mongo;

use App\Models\Acao;
use Jenssegers\Mongodb\Eloquent\Model;

class Carga extends Model
{
	protected $connection = 'mongodb';
	protected $collection = 'cargas';
    // protected $primaryKey = 'id';

	protected $fillable = [
		'title',
	];
}