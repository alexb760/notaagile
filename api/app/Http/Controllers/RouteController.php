<?php

namespace Incident\Http\Controllers;

use Illuminate\Http\Request;

use Incident\Http\Requests;
use Incident\Http\Controllers\Controller;

class RouteController extends BaseController
{
    protected $model = "Incident\Models\Route";

    //
    protected function getRules(Request $request)
    {
        return [
            "name" => 'max:100|alpha',
            "description" => 'required|max:255'
        ];
    }

    protected function getMessages()
    {
        return [
            "name.alpha" => "El nombre solo debe tener caracteres alfabeticos",
            "name.max" => "El nombre debe ser de maximo :max caracteres",
            "description.required" => "La descripcion es obligatoria",
            "description.max" => "La descripcion debe ser de maximo :max caracteres",
        ];
    }
}
