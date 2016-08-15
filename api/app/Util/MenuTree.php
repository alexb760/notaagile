<?php
/**
 * Created by PhpStorm.
 * User: Victor
 * Date: 15/08/2016
 * Time: 4:05 PM
 */

namespace Incident\Util;


use Incident\Models\Route;

class MenuTree
{

    public static function getByUserId($userId)
    {

        //Construimos el listado de elementos del menu
        $menuList = MenuTree::buildMenuList($userId);

        //Cosntruimos el arbol del menul
        $menuTree = MenuTree::buildTree($menuList);

        return $menuTree;
    }

    public static function getByProfileId($profileId)
    {

        $menuList = MenuTree::buildMenuList(null, $profileId);

        //Cosntruimos el arbol del menul
        $menuTree = MenuTree::buildTree($menuList);

        return $menuTree;

    }

    public static function getAll()
    {
        $menuList = MenuTree::buildMenuList(null, null);

        //Cosntruimos el arbol del menul
        $menuTree = MenuTree::buildTree($menuList);

        return $menuTree;
    }
    
    private static function buildMenuList($userId = null, $profileId = null)
    {
        $menuItemsList = array();

        //Obtengo todas las rutas a las que el usuario puede acceder
        $query = Route::select(['routes.id', 'routes.name', 'routes.label', 'routes.parent_id']);

        //Valido si se debe consultar por usuario o por perfil
        if (!is_null($userId)) {
            $query->join('route_profiles', 'routes.id', '=', 'route_profiles.route_id')
                ->join('profiles', 'route_profiles.profile_id', '=', 'profiles.id')
                ->join('user_profiles', 'profiles.id', '=', 'user_profiles.profile_id')
                ->where('user_profiles.user_id', '=', $userId);

        } else if (!is_null($profileId)) {

            $query->join('route_profiles', 'routes.id', '=', 'route_profiles.route_id')
                ->join('profiles', 'route_profiles.profile_id', '=', 'profiles.id')
                ->where('profiles.id', '=', $profileId);
        }


        $authorizedRoutes = $query->where('routes.isVisible', '=', 1)
            ->whereNotNull('routes.name')
            ->groupBy('routes.name')
            ->get()
            ->toArray();

        //Obtengo todos los parent que existen en la aplicacion, nos servirá para construir el menu
        $parentRoutes = Route::select(['routes.id', 'routes.name', 'label', 'parent_id'])
            ->whereNull('name')
            ->get()
            ->toArray();

        for ($i = 0; $i < count($authorizedRoutes); $i++) {

            //Si el item tiene padre obtengo todos sus ancestros
            if (!is_null($authorizedRoutes[$i]['parent_id'])) {
                $menuItemsList = MenuTree::getParent($authorizedRoutes[$i], $menuItemsList, $parentRoutes);
            }

        }

        $menuItemsList = array_merge($menuItemsList, $authorizedRoutes);

        //Ordeno el menu por su padre
        $menuItemsList = array_values(array_sort($menuItemsList, function ($value) {
            return $value['parent_id'];
        }));

        return $menuItemsList;
    }

    private static function getParent($item, $menuItemsList, $parentList)
    {

        //Revisamos que el parent se encuentre en el listado de elementos del menu
        if (array_search($item['parent_id'], array_column($menuItemsList, 'id')) === FALSE) {

            //Busco el padre y lo agrego al listado de menus
            $idxParent = array_search($item['parent_id'], array_column($parentList, 'id'));

            array_push($menuItemsList, $parentList[$idxParent]);

            //Vuelvo y llamo a la funcion de manera recursiva  para obtener los ancestros del padre
            if (!is_null($parentList[$idxParent]['parent_id'])) {
                $menuItemsList = MenuTree::getParent($parentList[$idxParent], $menuItemsList, $parentList);
            }

        }

        return $menuItemsList;

    }

    private static function buildTree($menuList, $parentId = 0)
    {
        $branch = array();

        foreach ($menuList as $element) {
            if ($element['parent_id'] == $parentId) {
                $children = MenuTree::buildTree($menuList, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }

        return $branch;

    }

}