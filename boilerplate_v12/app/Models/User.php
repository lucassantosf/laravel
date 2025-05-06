<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\Contracts\OAuthenticatable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Models\Role;  
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements OAuthenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
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

    /*public static function index(Request $request){
        return self::paginate(10);
    }

    public static function show(Request $request, $id){ 
        $user = self::where('id',$id)->first();
        return response()->json($user, 200);
    }

    public static function store($request){
        $user = self::create($request->all());
        $user->assignRole($request->role); 
        return response()->json($user, 200);
    }

    public static function update_one($request,$id){
        $user = self::where('id',$id)->first();
        $user->fill($request->all());
        $user->save(); 
        return response()->json($user, 200);
    }*/
}
