<?php namespace Database\Seeds;

use Seeder;
use HS\Models\User\UserGroup;

class SeedUserGroupsTable extends Seeder
{
    public function run()
    {
        UserGroup::create([
            'name' => 'Guest',
            'code' => 'guest',
            'description' => 'Default group for guest users.'
        ]);

        UserGroup::create([
            'name' => 'Registered',
            'code' => 'registered',
            'description' => 'Default group for registered users.'
        ]);
    }
}
