/**
 * Created by user on 19/04/2016.
 */

(function () {
    angular
        .module('app', ['ui.router', 'satellizer', 'ngStorage'])

        .constant('config', {
            APIURL: "http://api.notagile.com"
        })
        .config(["$stateProvider", "config", "$authProvider", "$locationProvider", "$urlRouterProvider",
            function ($stateProvider, config, $authProvider, $locationProvider, $urlRouterProvider) {

                //Configuraciones referentes al token
                $authProvider.httpInterceptor = function () {
                    return true;
                };
                $authProvider.loginUrl = config.APIURL + '/login';
                $authProvider.tokenHeader = 'Authorization';
                $authProvider.tokenType = 'Bearer';
                $authProvider.storageType = 'localStorage';

                //Rutas
                $urlRouterProvider.otherwise("/home");

                $stateProvider
                    .state("app", {
                        name: "app",
                        templateUrl: 'components/navbar/navbar.html',
                        controller: 'navbarController',
                        controllerAs: 'ctrl'
                    })
                    .state("app.home", {
                        name: "home",
                        url: "/home",
                        templateUrl: 'components/home/home.html',
                        controller: 'homeController',
                        controllerAs: 'ctrl'
                    })
                    .state("login", {
                        url: "/login",
                        templateUrl: 'components/login/login.html',
                        controller: 'loginController',
                        controllerAs: 'ctrl'
                    });

                $locationProvider.html5Mode(true);
            }])

        .run(["$rootScope", '$auth', '$state',
            function ($rootScope, $auth, $state) {
                $rootScope.$on('$stateChangeSuccess', function (event, next) {

                    var token = $auth.getToken() || false;

                    /*Si la URL a la que se va a acceder es el Login entonces se agrega una clase al body necesaria para
                     la vista del login*/
                    if (next.url == '/login') {
                        //Si esta autenticado lo redireccionamos al home
                        if (token || $auth.isAuthenticated()) {
                            $state.go("app.home");
                            return true;
                        }

                        $rootScope.bodyLayout = "login-layout blur-login";
                    } else {
                        $rootScope.bodyLayout = "no-skin";
                    }

                    //La siguiente condicion controla que el usuario este autenticado, si no lo esta redirecciona al login.
                    if (!token || !$auth.isAuthenticated()) {
                        $state.go("login");
                        return true;
                    }

                });

            }]);
})();
