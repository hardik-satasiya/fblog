<?php

use October\Rain\Database\Updates\Seeder;
use Illuminate\Database\Eloquent\Model as Eloquent;

class DatabaseSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();

        $this->call('Database\Seeds\SeedSetupAdmin');
        $this->call('Database\Seeds\SeedUserGroupsTable');
        $this->call('Database\Seeds\SeedSetupMailLayouts');
    }
}
