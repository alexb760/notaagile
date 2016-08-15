<?php

/**
 * Rutas que no necesitan autenticacion
 */
Route::group(['middleware' => 'cors'], function () {

    Route::get('/', function () {
        return view('welcome');
    });

    Route::post("login", 'UserController@login');
});

/**
 * Todas las rutas que deben proporcionar el token de autenticacion y requieren permisos.
 */
Route::group(['middleware' => ['cors', 'jwt.auth', 'check_permission']], function () {

    //CRUD y control de los datos de los usuarios
    Route::resource('user', 'UserController');


    //CRUD y control de los datos de los perfiles
    Route::resource('profile', 'ProfileController');
    Route::resource('userProfile', 'UserProfileController');

    //CRUD y control de los datos de las rutas
    Route::resource('route', 'RouteController');
    Route::resource('routeProfile', 'RouteController');

});


/**
 * Rutas que rsolo requieren un token valido, por lo general rutas que deberian ser alcanzadas por cualquier
 * usuario de la aplicación.
 */
Route::group(['middleware' => ['cors', 'check_token']], function () {

    //Rutas de seguridad
    Route::get("logout", "UserController@logout");
    Route::get("renewToken", "UserController@renewToken");

});