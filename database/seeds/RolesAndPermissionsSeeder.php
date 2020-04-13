<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

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
        app()['cache']->forget('spatie.permission.cache');
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create all available permissions
        Permission::create(['name' => 'Add Album']);
        Permission::create(['name' => 'Edit Album']);
        Permission::create(['name' => 'Delete Album']);

        Permission::create(['name' => 'Add Content']);
        Permission::create(['name' => 'Edit Content']);
        Permission::create(['name' => 'Delete Content']);

        // Default Roles
        $role           = Role::create(['name' => 'Administrator']);
        $allPermissions = Permission::all();
        $role->givePermissionTo($allPermissions);

        $role = Role::create(['name' => 'Content Creator']);
        $role->givePermissionTo('Add Album');
        $role->givePermissionTo('Edit Album');
        $role->givePermissionTo('Add Content');

        $role = Role::create(['name' => 'Subscriber']);

        // all existent users will be defaulting to the subscriber role
        foreach (User::all() as $user) {
            $user->assignRole('Subscriber');
        }
    }
}
