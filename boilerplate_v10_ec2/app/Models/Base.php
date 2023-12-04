<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Base extends Model
{
    use HasFactory;

    public static function index(Request $request)
    {     
        try {
            $return = self::query();
            foreach ($request->query() as $key=>$value)
            {
                $return->where($key,$value);
            }
            return $return->orderby('id','desc')->paginate(10); 
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public static function store(Request $request)
    {
        try {
            $resource = self::create($request->all());
            return $resource;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public static function show(Request $request, int $id)
    {
        try {
            $resource = self::findorFail($id);
            return $resource;
        } catch (\Throwable $th) {
            throw $th;
        }
    }  

    public static function update_one(Request $request, int $id)
    {    
        try {
            $resource = self::findorFail($id);
            $resource->fill($request->all());
            $resource->save();
            return $resource;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public static function destroy($id)
    {   
        try {
            $resource = self::findorFail($id);
            $resource->delete();
            return ['destroyed'=>true];
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    
}