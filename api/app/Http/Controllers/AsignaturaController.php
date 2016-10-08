<?php

namespace Incident\Http\Controllers;

use Illuminate\Http\Request;

use Incident\Http\Requests;
use Incident\Http\Controllers\Controller;

class AsignaturaController extends BaseController
{
    protected $model = "Incident\Models\Asignatura";

    //
    protected function getRules(Request $request)
    {
        return [
            "name" => 'required|max:100',
        ];
    }

    protected function getMessages()
    {
        return [
            "name.required" => "El nombre es requerido",
            "name.max" => "El nombre debe ser de maximo :max caracteres",
        ];
    }
}
