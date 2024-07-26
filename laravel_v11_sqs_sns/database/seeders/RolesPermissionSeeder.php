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

        // Reset cached 
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Creating permissions
        Permission::create(['name'=>'usuario.me',       'guard_name'=>'api']);
        Permission::create(['name'=>'usuario.logout',   'guard_name'=>'api']);
        Permission::create(['name'=>'usuario.index',    'guard_name'=>'api']);
        Permission::create(['name'=>'usuario.show',     'guard_name'=>'api']);
        Permission::create(['name'=>'usuario.store',    'guard_name'=>'api']);
        Permission::create(['name'=>'usuario.update',   'guard_name'=>'api']);
        Permission::create(['name'=>'usuario.destroy',  'guard_name'=>'api']);

        Permission::create(['name'=>'post.index',       'guard_name'=>'api']);
        Permission::create(['name'=>'post.show',        'guard_name'=>'api']);
        Permission::create(['name'=>'post.store',       'guard_name'=>'api']);
        Permission::create(['name'=>'post.update',      'guard_name'=>'api']);
        Permission::create(['name'=>'post.destroy',     'guard_name'=>'api']);
        Permission::create(['name'=>'post.job',         'guard_name'=>'api']);
        
        Permission::create(['name'=>'report.example',   'guard_name'=>'api']);
        Permission::create(['name'=>'document.validate','guard_name'=>'api']);
        Permission::create(['name'=>'debugs.sqs',       'guard_name'=>'api']);
        Permission::create(['name'=>'debugs.sns',       'guard_name'=>'api']);
        
        // Creating roles and sync permission to roles
        $role = Role::create(['name' => 'admin','guard_name'=>'api']);
        $role->givePermissionTo(Permission::all());

        $role_user = Role::create(['name' => 'client','guard_name'=>'api']);
        $role_user->givePermissionTo(['usuario.me','usuario.logout','post.index','post.show','post.store','post.update']);

        // Assign roles to users
        $users_admin = User::whereIn('id',[1])->get(); 
        foreach ($users_admin as $key => $admin) {
            DB::table('model_has_roles')->insert([
                'role_id'=>$role->id,
                'model_type'=>'App\Models\User',
                'model_id'=>$admin->id,
            ]);
        }

        // Reset cached  
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
