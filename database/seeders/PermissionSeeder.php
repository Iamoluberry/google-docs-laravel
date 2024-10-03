<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\permissionRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                "name" => "can edit user",
                "slug" => "can-edit-user",
            ],
            [
                "name" => "can delete user",
                "slug" => "can-delete-user",
            ],
            [
                "name" => "can view user",
                "slug" => "can-view-user",
            ],
            [
                "name" => "can add user",
                "slug" => "can-add-user",
            ],
            [
                "name" => "can update user",
                "slug" => "can-update-user",
            ],
            [
                "name" => "can edit post",
                "slug" => "can-edit-post",
            ],
            [
                "name" => "can delete post",
                "slug" => "can-delete-post",
            ],
            [
                "name" => "can view post",
                "slug" => "can-view-post",
            ],
            [
                "name" => "can add post",
                "slug" => "can-add-post",
            ],
            [
                "name" => "can update post",
                "slug" => "can-update-post",
            ],
            [
                "name" => "can add user to post",
                "slug" => "can-add-user-to-post",
            ],
            [
                "name" => "can create comment",
                "slug" => "can-create-comment",
            ],
            [
                "name" => "can edit comment",
                "slug" => "can-edit-comment",
            ],
            [
                "name" => "can delete comment",
                "slug" => "can-delete-comment",
            ]
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        $permissions = Permission::all();

        $admin = Role::where('slug', 'admin')->first();
        foreach ($permissions as $permission) {
            permissionRole::create([
                'role_id' => $admin->id,
                'permission_id' => $permission->id,
            ]);
        }

        $user = Role::where('slug', 'user')->first();

        permissionRole::create([
            'role_id' => $user->id,
            'permission_id' => $permissions[0]->id,
        ]);
        permissionRole::create([
            'role_id' => $user->id,
            'permission_id' => $permissions[2]->id,
        ]);
        permissionRole::create([
            'role_id' => $user->id,
            'permission_id' => $permissions[4]->id,
        ]);

        permissionRole::create([
            'role_id' => $user->id,
            'permission_id' => $permissions[5]->id,
        ]);

        permissionRole::create([
            'role_id' => $user->id,
            'permission_id' => $permissions[6]->id,
        ]);

        permissionRole::create([
            'role_id' => $user->id,
            'permission_id' => $permissions[7]->id,
        ]);

        permissionRole::create([
            'role_id' => $user->id,
            'permission_id' => $permissions[8]->id,
        ]);

        permissionRole::create([
            'role_id' => $user->id,
            'permission_id' => $permissions[9]->id,
        ]);

        permissionRole::create([
            'role_id' => $user->id,
            'permission_id' => $permissions[10]->id,
        ]);
        permissionRole::create([
            'role_id' => $user->id,
            'permission_id' => $permissions[11]->id,
        ]);
        permissionRole::create([
            'role_id' => $user->id,
            'permission_id' => $permissions[12]->id,
        ]);
        permissionRole::create([
            'role_id' => $user->id,
            'permission_id' => $permissions[13]->id,
        ]);

        $guest = Role::where('slug', 'guest')->first();
        permissionRole::create([
            'role_id' => $guest->id,
            'permission_id' => $permissions[7]->id,
        ]);
    }
}
