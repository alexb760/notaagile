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
Route::group(['middleware' => ['cors', 'jwt.auth']], function () {

    //Rutas de seguridad
    Route::get("/logout", "UserController@logout");
    Route::get("/renewToken", "UserController@renewToken");

    //CRUD y control de los datos de los usuarios
    Route::resource('user', 'UserController');


    //CRUD y control de los datos de los perfiles
    Route::resource('profile', 'ProfileController');
    Route::resource('userProfile', 'UserProfileController');

});
