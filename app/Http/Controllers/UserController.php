<?php

namespace Incident\Http\Controllers;

use Illuminate\Http\Request;
use Incident\Models\User as User;
use Incident\Http\Requests;
use JWTAuth;
use Incident\Http\Controllers\BaseController;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends BaseController
{


    public function __construct()
    {
        parent::__construct('User');
    }

    /**
     * Controla el inicio de sesion en la aplicacion.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse Si todo OK retorna el token.
     */
    public function login(Request $request)
    {
        // credenciales para loguear al usuario
        $credentials = $request->only('usuario', 'password');

        try {
            // si los datos de login no son correctos
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['invalid_credentials'], 401);
            }
        } catch (JWTException $ex) {
            return response()->json(['could_not_create_token'], 500);
        }

        //Obtenemos la información del usuario
        $user = User::where('usuario', "=", $request->input('usuario'))->get()->toArray();

        $user = $user[0];
        $user["token"] = $token;

        //Retornamos el token y la información del usuario
        return response()->json($user, 200);
    }

    /**
     * Controla el cierre de sesion en la aplicacion
     */

    public function logout()
    {
        try {
            //Verificamos que el usuario este logueado
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json('user_not_found', 400);
            }

            //Invalidamos el token
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json('user_logout', 200);

        } catch (JWTException $ex) {
            return response()->json('could_not_invalidate_token', 500);
        }
    }
}
