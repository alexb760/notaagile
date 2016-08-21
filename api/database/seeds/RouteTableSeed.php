<?php

use Illuminate\Database\Seeder;
use Incident\Models\Route;
use Incident\Models\RouteProfile;

class RouteTableSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $securityRoutes = Route::create(["label" => "SEGURIDAD", "description" => "Modulo de seguridad",
            "isVisible" => "1"]);

        $routes = array(["name" => "user", "label" => "USUARIOS", "description" => "Administracion de usuarios",
            "isVisible" => "1", "parent_id" => $securityRoutes->id],
            ["name" => "profile", "label" => "PERFILES", "description" => "Administración de perfiles",
                "isVisible" => "1", "parent_id" => $securityRoutes->id],
            ["name" => "userProfile", "label" => "PERFILES DE USUARIO",
                "description" => "Relación de perfiles con usuarios", "isVisible" => "1",
                "parent_id" => $securityRoutes->id],
            ["name" => "route", "label" => "RUTAS", "description" => "Administración de rutas", "isVisible" => "1",
                "parent_id" => $securityRoutes->id],
            ["name" => "routeProfile", "label" => "RUTAS DEL PERFIL", "description" => "Relacion de perfiles con rutas",
                "isVisible" => "1", "parent_id" => $securityRoutes->id],
        );

        $routesProfile = array();

        foreach ($routes as $route) {
            $routeDB = Route::create($route);
            array_push($routesProfile, $routeDB);
        }

        foreach ($routesProfile as $item) {
            RouteProfile::create(["method" => "*", "profile_id" => 1, "route_id" => $item->id]);
        }

        $langRoutes = Route::create(["label" => "IDIOMA", "description" => "Módulo de idioma", "isVisible" => "1"]);

        $routes = array(["name" => "lang", "label" => "LENGUAJE", "description" => "Administracion de lenguajes",
            "isVisible" => "1", "parent_id" => $langRoutes->id],
            ["name" => "labelLang", "label" => "ETIQUETAS", "description" => "Traducción de etiquetas",
                "isVisible" => "1", "parent_id" => $langRoutes->id]
        );

        foreach ($routes as $route) {
            $routeDB = Route::create($route);
            array_push($routesProfile, $routeDB);
        }

        foreach ($routesProfile as $item) {
            RouteProfile::create(["method" => "*", "profile_id" => 1, "route_id" => $item->id]);
        }

    }
}
