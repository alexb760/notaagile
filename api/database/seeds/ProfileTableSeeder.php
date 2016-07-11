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
            'nombre' => 'Admin',
            'descripcion' => 'Perfil del administrador',
            'created_by' => 1
        ]);
    }
}
