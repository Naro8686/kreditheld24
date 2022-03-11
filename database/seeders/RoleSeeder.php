<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = new Role();
        $admin->name = 'Administrator';
        $admin->slug = Role::ADMIN;
        $admin->save();

        $manager = new Role();
        $manager->name = 'Manager';
        $manager->slug = Role::MANAGER;
        $manager->save();
    }
}
