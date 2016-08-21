<?php

namespace Incident\Http\Controllers;

use Illuminate\Http\Request;

use Incident\Http\Requests;
use Incident\Http\Controllers\Controller;

class LabelLangController extends BaseController
{
    protected $model = "Incident\Models\LabelLang";
    protected $eager = ["lang"];

    protected function getRules(Request $request)
    {
        return [
            "label" => 'required|max:255',
            "def" => 'required|max:255',
            "lang_id" => 'required',
        ];
    }

    protected function getMessages()
    {
        return [
            "label.required" => "La etiqueta es requerida",
            "label.max" => "La etiqueta debe tener maximo :max caracteres",
            "def.required" => "La definición es requerida",
            "lang_id.required" => "El lenguaje es requerido",
            "def.max" => "La definición debe tener maximo :max caracteres",
        ];
    }
}
