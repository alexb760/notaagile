<?php

namespace Incident\Http\Controllers;


//use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Incident\Http\Requests;



class BaseController extends Controller
{
    use DispatchesJobs;

    protected $model;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function index()
    {
        return call_user_func($this->model.'::all');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $this->validate($request, call_user_func($this->model,'rules'), $this->getMessages($request->header($request->header('Lang'))));

        // $obj_model = new $this->baseModel;

        // return call_user_func($this->model,'create',$request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $searchResult = call_user_func($this->model.'::find',$id);

        if($searchResult == null)
            return response()->json(["error"=>"no_data_found"],400);
        return $searchResult;
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
        /*$response = call_user_func($this->model,'destroy',$id);

            //$this->baseModel::destroy($id);

        if ($response == 1) {
            return response()->json("OK", 200);
        } else {
            return response()->json("ERROR", 500);
        }*/
    }

    protected function formatValidationErrors(Validator $validator)
    {
        //return $validator->errors()->all();
    }

    /**
     * Metodo que se encarga de traducir los mensajes de error de Eloquent
     * @param $lang Lenguaje
     * @return $Array Array con los mensajes traducidos.
     */
    protected function getMessages($lang)
    {

        /*//Valido si el lenguaje existe, si no existe se trabaja con el ingles
        if (!array_key_exists($lang, Messages::$mensajes)) {
            $lang = "EN";
        }

        return Messages::$mensajes[$lang];*/
    }


    protected function storeError()
    {
        dd(DB::getQueryLog());
    }

}
