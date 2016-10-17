/**
 * Created by user on 19/04/2016.
 */
var app = angular.module("app", ['ngRoute', 'satellizer', 'ngStorage']);

app.constant('config', {
        APIURL: "http://api.notagile.com"
    })
    .config(["$routeProvider", "config", "$authProvider",
        function ($routeProvider, config, $authProvider) {

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
                    authorization: true
                })
                .when("/login", {
                    templateUrl: 'components/login/login.html',
                    controller: 'loginController',
                    authorization: false
                })
        }])

    .controller('loginController', ['$scope', 'config', '$auth', '$location', '$localStorage',
        function ($scope, config, $auth, $location, $localStorage) {
            $scope.$storage = $localStorage;
            $scope.login = function (user) {
                $auth.login(user).then(function (res) {
                    if (res.data) {
                        $scope.$storage.name = res.data.name;
                        $scope.$storage.email = res.data.email;
                        $scope.$storage.menu = res.data.menu;
                        $location.path("/home");
                    }
                }, function (res) {
                    console.log(res);
                    $.each(res.data, function (idx, val) {
                        toastr.error(val);
                    });
                });
            }
        }])

    .controller('homeController', ['$scope', 'config', '$localStorage', '$q', '$http', '$location',
        function ($scope, config, $localStorage, $q, $http, $location) {
            $scope.$storage = $localStorage;
            //los mandamos a la vista como user
            $scope.name = $scope.$storage.name;
            $scope.email = $scope.$storage.email;
            $scope.menu = $scope.$storage.menu;

            $scope.logout = function () {
                var deferred;
                deferred = $q.defer();
                $http({
                    method: 'GET',
                    skipAuthorization: false,//no queremos enviar el token en esta petición
                    url: config.APIURL + '/logout'
                })
                    .then(function (res) {
                        deferred.resolve(res);
                        $scope.$storage.$reset();
                        /*store.remove("satellizer_token");
                         store.remove("menu");
                         store.remove("name");
                         store.remove("email");*/
                        $location.path("/login");

                    })
                    .then(function (error) {
                        console.log(error);
                        deferred.reject(error);
                    });
                return deferred.promise;
            }

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
