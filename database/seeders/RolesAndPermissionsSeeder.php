<?php

namespace Database\Seeders;

use App\Enums\PermissionsEnum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        foreach (PermissionsEnum::cases() as $permission) {
            Permission::create(['name' => $permission->value, 'guard_name' => 'web']);
        }

        // Super Admin
        $superAdminRole = Role::create(['name' => 'super-admin']);
        $superAdminRole->givePermissionTo(PermissionsEnum::cases()); // All permissions

        // Admin
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo([
            PermissionsEnum::VIEW_USERS,
            PermissionsEnum::CREATE_USERS,
            PermissionsEnum::EDIT_USERS,
            PermissionsEnum::DELETE_USERS,

            PermissionsEnum::VIEW_DEVICES,
            PermissionsEnum::CREATE_DEVICES,
            PermissionsEnum::EDIT_DEVICES,
        ]);

        // Editor
        $editorRole = Role::create(['name' => 'editor']);
        $editorRole->givePermissionTo([
            PermissionsEnum::VIEW_DEVICES,
            PermissionsEnum::CREATE_DEVICES,
        ]);

        // Reader
        $readerRole = Role::create(['name' => 'reader']);
        $readerRole->givePermissionTo([
            PermissionsEnum::VIEW_DEVICES,
        ]);


        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ])->assignRole($superAdminRole);


        User::factory()->create([
            'name' => 'Test User',
            'email' => 'cem@example.com',
        ])->assignRole($adminRole);

    }
}
