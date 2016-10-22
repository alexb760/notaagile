/**
 * Created by user on 19/04/2016.
 */

(function () {
    angular
        .module('app', ['ngRoute', 'satellizer', 'ngStorage'])

        .constant('config', {
            APIURL: "http://api.notagile.com"
        })
        .config(["$routeProvider", "config", "$authProvider", "$locationProvider",
            function ($routeProvider, config, $authProvider, $locationProvider) {

                //Configuraciones referentes al token
                $authProvider.httpInterceptor = function () {
                    return true;
                };
                $authProvider.loginUrl = config.APIURL + '/login';
                $authProvider.tokenName = 'token';
                $authProvider.tokenHeader = 'Authorization';
                $authProvider.tokenType = 'Bearer';
                $authProvider.storageType = 'localStorage';

                $routeProvider.when('/', {
                        redirectTo: "/home"
                    })
                    .when("/home", {
                        templateUrl: 'components/home/home.html',
                        controller: 'homeController',
                        authorization: true,
                        controllerAs: 'ctrl'
                    })
                    .when("/login", {
                        templateUrl: 'components/login/login.html',
                        controller: 'loginController',
                        authorization: false,
                        controllerAs: 'ctrl'
                    });

                $locationProvider.html5Mode(true);
            }])

        .run(["$rootScope", '$location', '$auth',
            function ($rootScope, $location, $auth) {
                $rootScope.$on('$routeChangeStart', function (event, next) {

                    var token = $auth.getToken() || false;

                    /*Si la URL a la que se va a acceder es el Login entonces se agrega una clase al body necesaria para
                     la vista del login*/
                    if (next.originalPath == '/login') {
                        $rootScope.bodyLayout = "login-layout blur-login";
                    } else {
                        $rootScope.bodyLayout = "";
                    }

                    //La siguiente condicion controla que el usuario este autenticado, si no lo esta redirecciona al login.
                    if (!token || !$auth.isAuthenticated()) {
                        $location.path("/login");
                        return true;
                    }

                });

            }]);
})(angular);
