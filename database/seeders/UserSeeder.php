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
     *
     * @return void
     */
    public function run()
    {
        $adminRole = Role::where('slug', Role::ADMIN)->first();
        $createProposalsPermission = Permission::where('slug',Permission::CREATE_PROPOSALS)->first();

        $admin = new User();
        $admin->name = 'SuperAdmin';
        $admin->email = 'admin@panel.com';
        $admin->email_verified_at = now();
        $admin->password = '$2y$10$pMSfQw29K5Iz80lLhL2GbOCrJ30oAjwjXSkpZKShmufi6fwscDFiu';
        $admin->save();
        $admin->roles()->attach($adminRole);
        $admin->permissions()->attach($createProposalsPermission);
    }
}
