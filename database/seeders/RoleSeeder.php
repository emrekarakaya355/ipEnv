<?php

namespace  Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Permissions
        Permission::create(['name' => 'view role']);
        Permission::create(['name' => 'create role']);
        Permission::create(['name' => 'update role']);
        Permission::create(['name' => 'delete role']);

        Permission::create(['name' => 'view permission']);
        Permission::create(['name' => 'create permission']);
        Permission::create(['name' => 'update permission']);
        Permission::create(['name' => 'delete permission']);

        Permission::create(['name' => 'view user']);
        Permission::create(['name' => 'create user']);
        Permission::create(['name' => 'update user']);
        Permission::create(['name' => 'delete user']);

        Permission::create(['name' => 'view device']);
        Permission::create(['name' => 'create device']);
        Permission::create(['name' => 'update device']);
        Permission::create(['name' => 'delete device']);

        Permission::create(['name' => 'view deviceInfo']);
        Permission::create(['name' => 'create deviceInfo']);
        Permission::create(['name' => 'update deviceInfo']);
        Permission::create(['name' => 'delete deviceInfo']);

        Permission::create(['name' => 'view location']);
        Permission::create(['name' => 'create location']);
        Permission::create(['name' => 'update location']);
        Permission::create(['name' => 'delete location']);

        Permission::create(['name' => 'view deviceType']);
        Permission::create(['name' => 'create deviceType']);
        Permission::create(['name' => 'update deviceType']);
        Permission::create(['name' => 'delete deviceType']);
        Permission::create(['name' => 'view deleted devices']);


        Permission::create(['name' => 'view-device_movement']);
        Permission::create(['name' => 'view-device_family']);
        Permission::create(['name' => 'view-type']);
        Permission::create(['name' => 'view-brand']);
        Permission::create(['name' => 'view-model']);
        Permission::create(['name' => 'view-port_number']);
        Permission::create(['name' => 'view-serial_number']);
        Permission::create(['name' => 'view-registry_number']);
        Permission::create(['name' => 'view-mac_address']);
        Permission::create(['name' => 'view-device_name']);
        Permission::create(['name' => 'view-ip_address']);
        Permission::create(['name' => 'view-status']);

        Permission::create(['name' => 'view-building']);
        Permission::create(['name' => 'view-unit']);
        Permission::create(['name' => 'view-block']);
        Permission::create(['name' => 'view-floor']);
        Permission::create(['name' => 'view-room_number']);
        Permission::create(['name' => 'view-depo']);


        // Create Roles
        $superAdminRole = Role::create(['name' => 'super-admin']); //as super-admin
        $adminRole = Role::create(['name' => 'admin']);
        $staffRole = Role::create(['name' => 'tasinir']);
        $userRole = Role::create(['name' => 'user']);

        // Lets give all permission to super-admin role.
        $allPermissionNames = Permission::pluck('name')->toArray();

        $superAdminRole->givePermissionTo($allPermissionNames);

        // Let's give few permissions to admin role.
        $adminRole->givePermissionTo(['create role', 'view role', 'update role']);
        $adminRole->givePermissionTo(['create permission', 'view permission']);
        $adminRole->givePermissionTo(['create user', 'view user', 'update user']);
        $adminRole->givePermissionTo(['create device', 'view device', 'update device']);
        $adminRole->givePermissionTo(['create location', 'view location', 'update location']);
        $adminRole->givePermissionTo(['create deviceType', 'view deviceType', 'update deviceType']);
        $adminRole->givePermissionTo(['create deviceInfo', 'view deviceInfo', 'update deviceInfo']);

        // Let's Create User and assign Role to it.
        $superAdminUser = User::firstOrCreate([
            'email' => 'superadmin@gmail.com',
        ], [
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'username' => 'superadmin',
            'password' => Hash::make ('12345678'),
        ]);

        $superAdminUser->assignRole($superAdminRole);

    }
}
