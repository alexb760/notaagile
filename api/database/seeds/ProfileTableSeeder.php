<?php

use Illuminate\Database\Seeder;

class ProfileTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Incident\Models\Profile::create([
            'name' => 'Admin',
            'description' => 'Perfil del administrador',
            'created_by' => 1
        ]);
    }
}
