<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'usuario.me', 'guard_name'=>'api']);
        Permission::create(['name' => 'usuario.index', 'guard_name'=>'api']);
        Permission::create(['name' => 'usuario.store', 'guard_name'=>'api']);
        Permission::create(['name' => 'usuario.show', 'guard_name'=>'api']);
        Permission::create(['name' => 'usuario.update', 'guard_name'=>'api']);
        Permission::create(['name' => 'usuario.destroy', 'guard_name'=>'api']);

        Permission::create(['name' => 'role.index', 'guard_name'=>'api']);  
        
        // create roles and assign created permissions         
        $role = Role::create(['name' => 'admin', 'guard_name'=>'api']);
        $role->givePermissionTo(Permission::all());

        $role = Role::create(['name' => 'client', 'guard_name'=>'api'])
            ->givePermissionTo([ 
                'usuario.me',  
            ]); 
    }
}
