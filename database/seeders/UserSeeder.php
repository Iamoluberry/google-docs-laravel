<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Olanrewaju',
            'email' => 'olanrewajuakilu@gmail.com',
            'password' => bcrypt('password'),
            'role_id' => 1,
        ]);

        User::factory()->create([
            'name' => 'cocomelon',
            'email' => 'cocomelon@gmail.com',
            'password' => bcrypt('password'),
            'role_id' => 2,
        ]);

        User::factory()->create([
            'name' => 'larry',
            'email' => 'larry@gmail.com',
            'password' => bcrypt('password'),
            'role_id' => 3,
        ]);
    }
}
