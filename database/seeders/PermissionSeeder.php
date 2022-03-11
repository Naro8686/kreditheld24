<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $createTasks = new Permission();
        $createTasks->name = 'Create Proposals';
        $createTasks->slug = Permission::CREATE_PROPOSALS;
        $createTasks->save();
    }
}
