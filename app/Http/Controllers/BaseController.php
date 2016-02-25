<?php

namespace Incident\Http\Controllers;


use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;

use Incident\Http\Requests;
use Incident\Http\Controllers\Controller;

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


        $this->validate($request, $this->baseModel::rules());

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
        return $this->baseModel::findOrFail($id);
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
            return "OK";
        } else {
            return "ERROR";
        }
    }

    protected function formatValidationErrors(Validator $validator)
    {
        return $validator->errors()->all();
    }
}
