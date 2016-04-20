/**
 * Created by user on 19/04/2016.
 */
var app = angular.module("app", ['ngRoute', 'angular-jwt', 'angular-storage']);

app.constant('CONFIG', {
        APIURL: "http://api.incident.dev/"
    })
    .config(["$routeProvider", "$httpProvider", "jwtInterceptorProvider",
        function ($routeProvider, $httpProvider, jwtInterceptorProvider) {
            $httpProvider.defaults.headers.common["X-Requested-With"] = 'XMLHttpRequest';

            //en cada petición enviamos el token a través de los headers con el nombre Authorization
            jwtInterceptorProvider.tokenGetter = function () {
                return localStorage.getItem('token');
            };
            $httpProvider.interceptors.push('jwtInterceptor');

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

    .controller('loginController', ['$scope', 'CONFIG', 'authFactory', 'jwtHelper', 'store', '$location',
        function ($scope, CONFIG, authFactory, jwtHelper, store, $location) {
            $scope.login = function (user) {
                authFactory.login(user).then(function (res) {
                    if (res.data) {
                        store.set('token', res.data.token);
                        store.set('nombre', res.data.nombre);
                        store.set('email', res.data.email);
                        $location.path("/home");
                    }
                });
            }
        }])

    .factory("authFactory", ["$http", "$q", "CONFIG", function ($http, $q, CONFIG) {
        return {
            login: function (user) {
                var deferred;
                deferred = $q.defer();
                $http({
                    method: 'POST',
                    skipAuthorization: true,//no queremos enviar el token en esta petición
                    url: CONFIG.APIURL + '/login',
                    data: "usuario=" + user.usuario + "&password=" + user.password,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                })
                    .then(function (res) {
                        deferred.resolve(res);
                    })
                    .then(function (error) {
                        deferred.reject(error);
                    });
                return deferred.promise;
            }
        }
    }])


    .controller('homeController', ['$scope', 'CONFIG', 'jwtHelper', 'store', '$q', '$http', '$location',
        function ($scope, CONFIG, jwtHelper, store, $q, $http, $location) {
            //obtenemos el token en localStorage
            var token = store.get("token");
            //los mandamos a la vista como user
            $scope.user = store.get("nombre");
            $scope.email = store.get("email");

            $scope.logout = function () {
                var deferred;
                deferred = $q.defer();
                $http({
                    method: 'GET',
                    skipAuthorization: false,//es necesario enviar el token
                    url: CONFIG.APIURL + 'logout'
                })
                    .then(function (res) {
                        deferred.resolve(res);
                        $location.path("/login");

                    })
                    .then(function (error) {
                        console.log(error);
                        deferred.reject(error);
                    })
                return deferred.promise;
            }

        }])

    .run(["$rootScope", 'jwtHelper', 'store', '$location',
        function ($rootScope, jwtHelper, store, $location) {
            $rootScope.$on('$routeChangeStart', function (event, next) {
                var token = store.get("token") || null;
                if (!token)
                    $location.path("/login");

                var bool = jwtHelper.isTokenExpired(token);
                if (bool === true)
                    $location.path("/login");
            });
        }])