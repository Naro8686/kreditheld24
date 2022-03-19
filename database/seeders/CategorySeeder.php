<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Permission;
use App\Models\Proposal;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = collect(Proposal::$creditTypes);
        $parents = $types->flip()->only(['private_credit', 'home'])->flip();
        $children = $types->flip()->except($parents->toArray())->flip();
        foreach ($parents as $parent_value):
            $category = Category::updateOrCreate(['name' => trans("proposal.creditTypes.$parent_value")]);
            foreach ($children as $child_value) $category->children()->updateOrCreate([
                    'name' => trans("proposal.creditTypes.$child_value")
                ]);
        endforeach;
    }
}
