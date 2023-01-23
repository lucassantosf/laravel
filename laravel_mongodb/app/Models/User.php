<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Authenticatable;
// use Illuminate\Foundation\Auth\User as Authenticatable;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Laravel\Passport\HasApiTokens;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;
 
class User extends Eloquent implements AuthenticatableContract
{
    use Authenticatable, HasApiTokens, HasFactory, Notifiable;

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
            'role' => 'required|in:'.implode(',',Role::all()->pluck('name')->toArray()),
            "document" => "string|unique:users,document",
        ];
    } 

    public static function arrUpdateValidation($id)
    {
        return [
            "name" => "string|max:255",
            "email" => "string|email|max:255|unique:users,email,".$id.",id",
            "document" => "string|unique:users,document,$id",
            "password" => "string|min:8|confirmed",
            'role' => 'in:'.implode(',',Role::all()->pluck('name')->toArray()),
            'phone_number' => 'string|max:13',
        ];
    }

    public static function index(){
        return self::all();
    }

}
