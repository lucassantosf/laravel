<?php

namespace App\Models;

class Post extends Base
{
    protected $table = 'posts'; 

    protected $fillable = [
        'user_id',  
        'title',
        'content',
    ]; 

    public static function arrValidation(){
        return [
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string',
            'content' => 'required|string',
        ];
    } 

    public static function arrUpdateValidation($id){
        return [
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string',
            'content' => 'required|string',
        ];
    } 

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}