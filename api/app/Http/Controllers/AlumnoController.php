<?php

namespace Incident\Http\Controllers;


use Illuminate\Http\Request;

class AlumnoController extends BaseController
{

    protected $model='Incident\Models\Alumno';

    protected function getRules(Request $request)
    {
        return [
            "nombres" => 'required|max:200',
            "apellidos" => 'required|max:200',
            "doc_identificacion" => 'required|max:200',
        ];
    }

    protected function getMessages()
    {
        return [
            "nombres.required" => "El nombre es requerido",
            "nombres.max" => "El nombre debe ser de maximo :max caracteres",
            "apellidos.required" => "El apellido es requerido",
            "apellidos.max" => "El apellido debe ser de maximo :max caracteres",
            "doc_identificacion.required" => "El documento de identificación es requerido",
            "doc_identificacion.max" => "El documento de identificación debe ser de maximo :max caracteres",
        ];
    }
}
