<?php

namespace App\Mongo;

use App\Models\Acao;
use Jenssegers\Mongodb\Eloquent\Model;

class User extends Model
{
	protected $connection = 'mongodb';
	protected $collection = 'users';
    // protected $primaryKey = 'id';

	protected $fillable = [
		'name',
	];
}