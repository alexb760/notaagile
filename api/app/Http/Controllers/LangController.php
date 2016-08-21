<?php

namespace Incident\Http\Controllers;

use Illuminate\Http\Request;

use Incident\Http\Requests;
use Incident\Http\Controllers\Controller;
use Incident\Models\Lang;

class LangController extends BaseController
{

    protected $model = "Incident\Models\Lang";
    protected $eager = ["labels"];

    protected function getRules(Request $request)
    {

        if ($request->isMethod('post')) {
            return [
                "code" => 'required|max:4|alpha|unique:langs',
                "name" => 'required|max:255'
            ];
        } else if ($request->isMethod('put')) {
            return ['name' => 'required|max:255',
                'code' => 'required|max:4|alpha|unique:langs,code,' . $request->input('id'),
            ];
        }


        return array();
    }

    protected function getMessages()
    {
        return [
            "name.required" => "El nombre es requerido",
            "name.max" => "El nombre debe tener maximo :max caracteres",
            "code.required" => "El código es requerido",
            "code.unique" => "El código está duplicado",
            "code.max" => "El código debe tener maximo :max caracteres",
        ];
    }

    /*
     * Retorna todas las etiquetas de un lenguaje.
     * $code = Es el código ISO del lenguaje
     */
    public function getLabelsByLang($code)
    {
        $response = Lang::with("labels")->where("code", $code)->get();

        return response()->json($response, 200);
    }
}
