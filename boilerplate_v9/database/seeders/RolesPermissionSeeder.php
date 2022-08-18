<?php

namespace Database\Seeders;

use App\Models\User;
use DB;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->delete();
        DB::table('roles')->delete(); 

        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name'=>'usuario.me','guard_name'=>'api']);
        Permission::create(['name'=>'usuario.index','guard_name'=>'api']);
        
        $role = Role::create(['name' => 'admin','guard_name'=>'api']);
        $role->givePermissionTo(Permission::all());

        $role_user = Role::create(['name' => 'cliente','guard_name'=>'api']);
        $role_user->givePermissionTo(['usuario.me']);

        $users_admin = User::whereIn('id',[1])->get(); 
        $admin = Role::findByName('admin'); 
        $admin->users()->attach($users_admin);
    }
}
