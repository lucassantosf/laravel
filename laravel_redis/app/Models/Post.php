<?php

namespace App\Models;

use App\Jobs\FirstJob;
use Illuminate\Http\Request; 

class Post extends Base
{
    protected $table = 'posts'; 

    protected $fillable = [
        'user_id',  
        'title',
        'content',
        'status',
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
            'user_id' => 'exists:users,id',
            'title' => 'string',
            'content' => 'string',
        ];
    } 

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public static function store(Request $request)
    {
        try {
            FirstJob::dispatch($request->all())->onQueue('DEFAULT')->onConnection('redis');
            return response()->json(['success'=>true], 200); 
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}