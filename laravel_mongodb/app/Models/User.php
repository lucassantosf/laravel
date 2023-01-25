<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Authenticatable;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;

class User extends Eloquent implements AuthenticatableContract
{
    use HasRoles,Authenticatable, HasApiTokens, HasFactory, Notifiable;

    protected $connection = 'mongodb';
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

    public static function index(){
        return self::all();
    }

    public static function show($id){
        $user = self::where('_id',$id)->first();
        return response()->json($user, 200);
    }

    public static function store($request){
        $user = self::create($request->all());
        return response()->json($user, 200);
    }

    public static function update_one($request,$id){
        $user = self::where('_id',$id)->first();
        $user->fill($request->all());
        $user->save(); 
        return response()->json($user, 200);
    }

}
