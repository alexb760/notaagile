<?php

/**
 * Rutas que no necesitan autenticacion
 */
Route::group(['middleware' => 'cors'], function () {

	Route::get('/', function () {
		return view('welcome');
	});

	Route::post("/login", 'UserController@login');
});

/**
 * Todas las rutas que deben proporcionar el token de autenticacion.
 */
Route::group(['middleware' => ['cors', 'jwt.auth']], function() {
	Route::resource('user','UserController');

	Route::get("/logout","UserController@logout");

	Route::get('/primeraView',function ()
	{
		if ( ! $user = \JWTAuth::parseToken()->authenticate() ) {
			return response()->json(['User Not Found'], 404);
		}

		$getTimeStamp = date('Y/m/d H:i:s');
		$date = new \DateTime($getTimeStamp);

		$token = \JWTAuth::getToken();
		$newToken = \JWTAuth::refresh($token);

		//Vista con parametros
		return response()->json([
			'fecha'=>$date->format('Y-m-d'),
			'hora'=>$date->format('H'),
			'minuto'=>$date->format('i'),
			'zh'=>Config::get('app.timezone'),
			'token' => $newToken
		]);
	});
});
