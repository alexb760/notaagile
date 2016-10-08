<?php

namespace Incident\Http\Controllers;

use Illuminate\Http\Request;

use Incident\Http\Requests;
use Incident\Http\Controllers\Controller;

class CursoController extends BaseController
{

    protected $model = "Incident\Models\Curso";

    //
    protected function getRules(Request $request)
    {
        return [
            "anio" => 'required|min:2000|max:2020|numeric',
            "periodo" => 'required|min:1|max:2|numeric'
        ];
    }

    protected function getMessages()
    {
        return [
            "anio.required" => "El año es requerido",
            "periodo.required" =>"El periodo es requerido",
            "anio.numeric" => "El año debe ser numerico",
            "periodo.numeric" => "El periodo debe ser numerico",
            "anio.min" => "El año no puede ser menor al año 2000",
            "anio.max" => "El año no puede ser mayor al año 2020",
            "periodo.min" => "El periodo no puede ser menor a 1",
            "periodo.max" => "El periodo no puede ser mayor a 2"

        ];
    }
}