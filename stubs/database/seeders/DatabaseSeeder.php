<?php

namespace Database\Seeders;

use App\Assets\Utils;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $admin_role = Role::factory()->create([
            "name" => "Admin",
            "permissions" => Utils::$adminPermissions
        ]);

        $branch_role = Role::factory()->create([
            "name" => "Branch",
            "permissions" => Utils::$branchPermissions
        ]);

        User::factory()->create([
            "name" => "Admin",
            "email" => "admin@gmail.com",
            "password" => Hash::make("admin123456789"),
            "role_id" => $admin_role->id
        ]);
    }
}
