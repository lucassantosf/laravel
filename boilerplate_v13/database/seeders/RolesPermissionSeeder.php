<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'usuario.me', 'usuario.logout', 'usuario.index', 'usuario.show', 'usuario.store', 'usuario.update', 'usuario.destroy',
            'post.index', 'post.show', 'post.store', 'post.update', 'post.destroy', 'post.job',
            'report.example', 'document.validate'
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'api');
        }

        // Create roles and assign existing permissions
        $adminRole = Role::findOrCreate('admin', 'api');
        $adminRole->givePermissionTo(Permission::all());

        $clientRole = Role::findOrCreate('client', 'api');
        $clientRole->givePermissionTo([
            'usuario.me', 
            'usuario.index',
            'usuario.logout', 
            'post.index', 
            'post.show', 
        ]);

        // Assign admin role to the master user
        $admin = User::where('email', 'master@gmail.com')->first();
        if ($admin) {
            $admin->assignRole($adminRole);
        }

        // Assign client role to the client user
        $client = User::where('email', 'client@gmail.com')->first();
        if ($client) {
            $client->assignRole($clientRole);
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
