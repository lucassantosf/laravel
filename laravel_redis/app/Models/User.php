<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Http\Request;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    } 

    public static function arrValidation()
    {
        return [
            'name' => 'required|string',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ];
    } 

    public static function arrUpdateValidation($id)
    {
        return [
            "name" => "string|max:255",
            "email" => "string|email|max:255|unique:users,email,".$id.",id",
            "password" => "string|min:8|confirmed",
        ];
    }

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
