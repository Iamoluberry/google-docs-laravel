<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                "name" => "admin",
                "slug" => "admin",
            ],
            [
                "name" => "user",
                "slug" => "user",
            ],
            [
                "name" => "guest",
                "slug" => "guest",
            ]
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
