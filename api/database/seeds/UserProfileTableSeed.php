<?php

use Illuminate\Database\Seeder;

class UserProfileTableSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Incident\Models\UserProfile::create(["user_id" => 1, "profile_id" => 1]);
    }
}
