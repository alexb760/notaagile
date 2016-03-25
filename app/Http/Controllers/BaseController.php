<?php

namespace Incident\Http\Controllers;


use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;

use Incident\Helppers\Messages as Messages;
use Illuminate\Support\Facades\DB;
use Incident\Http\Requests;

use Incident\Http\Controllers\Controller;
use Mockery\CountValidator\Exception;


class BaseController extends Controller
{
    use DispatchesJobs, ValidatesRequests;

    protected $baseModel;

    function __construct($model)
    {
        $this->middleware('jwt.auth', ['except' => ['login', 'index']]);
        $model = "Incident\\Models\\" . $model;
        $this->baseModel = new $model;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function index()
    {
        return $this->baseModel::all();

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->baseModel::rules(), $this->getMessages($request->header($request->header('Lang'))));

        $obj_model = new $this->baseModel;

        return $obj_model->create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {

            return $this->baseModel::findOrFail($id);

        } catch (ModelNotFoundException $ex) {

            $this->storeError();
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $response = $this->baseModel::destroy($id);

        if ($response == 1) {
            return response()->json("OK", 200);
        } else {
            return response()->json("ERROR", 500);
        }
    }

    protected function formatValidationErrors(Validator $validator)
    {
        return $validator->errors()->all();
    }

    /**
     * Metodo que se encarga de traducir los mensajes de error de Eloquent
     * @param $lang Lenguaje
     * @return $Array Array con los mensajes traducidos.
     */
    protected function getMessages($lang)
    {

        //Valido si el lenguaje existe, si no existe se trabaja con el ingles
        if (!array_key_exists($lang, Messages::$mensajes)) {
            $lang = "EN";
        }

        return Messages::$mensajes[$lang];
    }


    protected function storeError()
    {
        dd(DB::getQueryLog());
    }

}
