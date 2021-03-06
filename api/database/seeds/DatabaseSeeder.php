<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserTableSeeder::class);
        $this->call(ProfileTableSeeder::class);
        $this->call(UserProfileTableSeed::class);
        $this->call(RouteTableSeed::class);
        $this->call(LangTableSeed::class);
    }
}
