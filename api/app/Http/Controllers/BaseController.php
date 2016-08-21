<?php

namespace Incident\Http\Controllers;


use Incident\Models\AppError;
use Response;
use Validator;
use Log;
use JWTAuth;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;

use Incident\Http\Requests;


abstract class BaseController extends Controller
{

    protected $model;

    //Columnas customs que se agregan en el modelo, como por ejemplo la información de las foraneas
    protected $eager = array();

    //Columnas que pueden ser actualizados
    protected $updatable = array();

    abstract protected function getRules(Request $request);

    abstract protected function getMessages();


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function index()
    {

        if (is_array($this->eager) && count($this->eager) > 0)
            return response()->json(call_user_func($this->model . '::with', $this->eager)->get());

        return response()->json(call_user_func($this->model . '::all'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $errors = $this->validateRequest($request);

        if (count($errors) == 0) {

            try {

                return response()->json(call_user_func($this->model . '::create', $request->all()));
            } catch (\Exception $e) {
                $log = $this->createLog($e->getMessage(), $request->all());
                return response()->json(["error" => "internal_error", "code_id" => $log], 500);

            }

        } else {
            return response()->json(["error" => $errors], 400);

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        if (is_array($this->eager) && count($this->eager) > 0)
            $searchResult = call_user_func($this->model . '::with', $this->eager)->find($id);
        else
            $searchResult = call_user_func($this->model . '::find', $id);

        if ($searchResult == null)
            return response()->json(["error" => "no_data_found"], 400);

        return $searchResult;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $searchResult = call_user_func($this->model . '::find', $id);

        if (isset($searchResult)) {

            try {

                $response = $searchResult->delete();
                return response()->json("OK", 200);

            } catch (\Exception $e) {
                $log = $this->createLog($e->getMessage(), $id);
                return response()->json(["error" => "internal_error", "code_id" => $log], 500);

            }

        } else {
            return response()->json(["error" => "no_data_found"], 400);
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $errors = $this->validateRequest($request);

        if (count($errors) == 0) {
            //Revisamos que exista el registro
            $searchResult = call_user_func($this->model . '::find', $id);

            if (isset($searchResult)) {

                //Filtramos los campos que pueden ser actualizados
                if (is_array($this->updatable) && count($this->updatable) > 0)
                    $input = $request->only($this->updatable);
                else
                    $input = $request->all();

                try {
                    $searchResult->update($input);

                    //Retornamos los datos actualizados.
                    return call_user_func($this->model . '::find', $id);

                } catch (\Exception $e) {
                    $log = $this->createLog($e->getMessage(), $id);
                    return response()->json(["error" => "internal_error", "code_id" => $log], 500);

                }

            } else {
                return response()->json(["error" => "no_data_found"], 400);
            }
        } else {

            return response()->json(["error" => $errors], 400);

        }

    }

    /**
     * Metodo encargado de validar la peticion segun la configuracion de las rules y los mensajes.
     * @param Request $request
     * @return array
     */
    protected function validateRequest(Request $request)
    {


        $validator = Validator::make($request->all(), $this->getRules($request), $this->getMessages());

        if ($validator->fails()) {

            return $errors = $validator->errors()->all();
        }

        return array();
    }

    /**
     * Tabla encargada de registrar errores en la tabla de app_errors
     * @param $message Mensaje de error
     * @param $data Data con la que se estaba trabajando
     */
    protected function createLog($message, $data)
    {

        //Obtenemos el id del usuario logueado
        $user = JWTAuth::parseToken()->toUser();

        //Obtenemos la clase en donde se generó el error
        $class = get_class($this);

        $errorData = array(
            "user_id" => $user->id,
            "php_class" => $class,
            "message" => $message,
            "work_data" => json_encode($data)
        );

        $appError = AppError::create($errorData);

        Log::error(
            "\nUSER: " . $user->email . "\n"
            . "PHP_CLASS: " . $errorData["php_class"] . "\n"
            . "WORK_DATA: " . $errorData["work_data"] . "\n"
            . "MESSAGE: " . $errorData["message"] . "\n"
        );

        return $appError->id;

    }
}
