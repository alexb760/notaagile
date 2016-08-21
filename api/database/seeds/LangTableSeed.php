<?php

use Illuminate\Database\Seeder;
use Incident\Models\LabelLang;
use Incident\Models\Lang;

class LangTableSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $esLang = Lang::create(["code" => "ES", "name" => "Español"]);

        $labelLangs = array(
            ["label" => "internal_error", "def" => "Error interno", "lang_id" => $esLang->id],
            ["label" => "no_data_found", "def" => "Datos no encontrados", "lang_id" => $esLang->id],
        );

        foreach ($labelLangs as $labelLang) {
            LabelLang::create($labelLang);
        }
        
    }
}
