<?php

namespace Incident\Http\Controllers;

use Illuminate\Http\Request;

use Incident\Http\Requests;
use Incident\Models\Profile;
use JWTAuth;

class ProfileController extends BaseController
{
    protected $model = 'Incident\Models\Profile';
    protected $eager = array('creator');
    protected $updatable = array('name','description');

    protected function getMessages()
    {
        return [
            "name.required" => "El nombre es requerido",
            "name.max" => "El nombre debe tener maximo :max caracteres",
            "name.unique" => "El nombre esta duplicado",
        ];
    }

    protected function getRules(Request $request)
    {

        if ($request->isMethod('post')) {
            return [
                'name' => 'required|max:100|unique:profiles',
            ];
        } elseif ($request->isMethod('put')) {
            return [
                'name' => 'required|max:100|unique:profiles,nombre,' . $request->input('id'),
            ];
        }

        return array();
    }

    public function store(Request $request)
    {
        $errors = $this->validateRequest($request);

        if (count($errors) == 0) {
            $profile = $request->all();

            //Obtenemos el ID de la persona que esta registrando el perfil
            $user = JWTAuth::parseToken()->authenticate();
            $userId = $user->id;
            $profile['created_by'] =$userId;

            $response = Profile::create($profile);

            return response()->json($response, 201);
        } else {
            return response()->json(["error" => $errors], 400);
        }
    }
}
