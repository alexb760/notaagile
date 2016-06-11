<?php

namespace Incident\Http\Controllers;

use Incident\Models\User as User;
use Incident\Http\Requests;

use JWTAuth;
use Hash;

use Tymon\JWTAuth\Exceptions\JWTException;

use Illuminate\Http\Request;


class UserController extends BaseController
{

    protected $model ="Incident\\Models\\User";

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


    /**
     * Se sobreescribe este metodo solo para encriptar la contraseña del usuario.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, User::rules(), $this->getMessages($request->header('Lang')));

        $user = $request->all();

        //Se encripta la contraseña antes de enviarla a la BD
        $user['password'] = bcrypt($user['password']);

        $response = User::create($user);

        return response()->json($response, 201);
    }
}
