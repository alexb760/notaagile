<?php

namespace Incident\Http\Controllers;

use Incident\Models\User as User;
use Incident\Http\Requests;

use JWTAuth;
use Hash;

use Tymon\JWTAuth\Exceptions\JWTException;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;


class UserController extends BaseController
{

    protected $model = "Incident\\Models\\User";

    protected $updatable = array('name', 'isActive', 'email');

    /**
     * Controla el inicio de sesion en la aplicacion.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse Si todo OK retorna el token.
     */
    public function login(Request $request)
    {
        // credenciales para loguear al usuario
        $credentials = $request->only('email', 'password');

        try {
            // si los datos de login no son correctos
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['invalid_credentials'], 401);
            }
        } catch (JWTException $ex) {
            return response()->json(['could_not_create_token'], 500);
        }

        //Obtenemos la información del usuario
        $user = User::where('email', "=", $request->input('email'))->get()->first()->toArray();
        $user["token"] = $token;

        //Revisamos que el usuario se encuentre activo
        if (!$user['isActive']) {
            JWTAuth::invalidate($user["token"]);
            return response()->json('inactive_user', 401);
        }

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
     * Funcion encargada de renovar el token de la sesion.
     */
    public function renewToken()
    {

        $token = JWTAuth::getToken();
        try {
            $token = JWTAuth::refresh($token);

            return response()->json(["token" => $token]);

        } catch (TokenInvalidException $e) {
            return response()->json("invalid_token", 400);
        }
    }

    /**
     * Se sobreescribe este metodo solo para encriptar la contraseña del usuario.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $errors = $this->validateRequest($request);

        if (count($errors) == 0) {
            $user = $request->all();

            //Se encripta la contraseña antes de enviarla a la BD
            $user['password'] = bcrypt($user['password']);

            $response = User::create($user);

            return response()->json($response, 201);
        } else {
            return response()->json(["error" => $errors], 400);
        }
    }

    protected function getRules(Request $request)
    {

        if ($request->isMethod('post')) {
            return [
                'name' => 'required|max:100',
                'password' => 'required|min:8',
                'email' => 'required|email|unique:users',
                'isActive' => 'required|boolean'
            ];
        } else if ($request->isMethod('put')) {
            return ['name' => 'required|max:100',
                'isActive' => 'required|boolean',
                'email' => 'required|email|unique:users,email,' . $request->input('id'),
            ];
        }

        return array();

    }

    protected function getMessages()
    {
        return [
            'nombre.required' => "El nombre es requerido.",
            'email.required' => 'El email es requerido.',
            'isActive.required' => 'El campo isActive es requerido.',
            'nombre.max' => 'El nombre debe tener maximo :max catacteres.',
            'password.min' => 'La contraseña debe tener minimo :min catacteres.',
            'isActive.in' => 'El campo isActive debe ser del siguiente tipo: :values',
            'email.unique' => 'El email esta duplicado'
        ];
    }
}
