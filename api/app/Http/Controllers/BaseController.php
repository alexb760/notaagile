<?php

namespace Incident\Http\Controllers;


use Response;
use Validator;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Incident\Http\Requests;


abstract class BaseController extends Controller
{

    protected $model;

    abstract protected function getRules(Request $request);

    abstract protected function getMessages();


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function index()
    {
        return call_user_func($this->model . '::all');
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

            $response = call_user_func($this->model . '::create', $request->all());

            return call_user_func($this->model . '::find', $response);

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

        if (isset($searchResult)){
            $response = call_user_func($this->model.'::destroy',$id);

            if ($response == 1) {
                return response()->json("OK", 200);
            } else {
                return response()->json("ERROR", 400);
            }
        }else{
            return response()->json(["error" => "no_data_found"], 400);
        }

    }

    protected function validateRequest(Request $request)
    {


        $validator = Validator::make($request->all(), $this->getRules($request), $this->getMessages());

        if ($validator->fails()) {

            return $errors = $validator->errors()->all();
        }

        return array();
    }


    protected function storeError()
    {
        dd(DB::getQueryLog());
    }

}
