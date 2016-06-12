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
	
});
