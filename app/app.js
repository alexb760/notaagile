/**
 * Created by user on 19/04/2016.
 */
var app = angular.module("app", ['ngRoute', 'angular-jwt', 'angular-storage']);

app.constant('CONFIG', {
        APIURL: "http://api.notagile.com"
    })
    .config(["$routeProvider", "$httpProvider", "CONFIG", "jwtInterceptorProvider",
        function ($routeProvider, $httpProvider, CONFIG, jwtInterceptorProvider) {

            //Tenemos que meter la url de la api a la lista de sitios seguros
            jwtInterceptorProvider.whiteListedDomains = [CONFIG.APIURL];

            $httpProvider.interceptors.push('requestTokenInterceptor');

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
                        store.set('name', res.data.name);
                        store.set('email', res.data.email);
                        store.set('menu', res.data.menu);
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

    .factory("authFactory", ["$http", "$q", "CONFIG", function ($http, $q, CONFIG) {
        return {
            login: function (user) {
                var deferred;
                deferred = $q.defer();
                $http({
                    method: 'POST',
                    skipAuthorization: true,//no queremos enviar el token en esta petición
                    url: CONFIG.APIURL + '/login',
                    data: user
                })
                    .then(function (res) {
                        deferred.resolve(res);
                    }, function (error) {
                        deferred.reject(error);
                    });
                return deferred.promise;
            }
        }
    }])

    .controller('homeController', ['$scope', 'CONFIG', 'store', '$q', '$http', '$location',
        function ($scope, CONFIG, store, $q, $http, $location) {
            //obtenemos el token en localStorage
            var token = store.get("token");
            //los mandamos a la vista como user
            $scope.name = store.get("name");
            $scope.email = store.get("email");
            $scope.menu = store.get("menu");

            $scope.logout = function () {
                var deferred;
                deferred = $q.defer();
                $http({
                    method: 'GET',
                    skipAuthorization: false,//no queremos enviar el token en esta petición
                    url: CONFIG.APIURL + '/logout'
                })
                    .then(function (res) {
                        deferred.resolve(res);
                        store.remove("token");
                        store.remove("name");
                        store.remove("email");
                        $location.path("/login");

                    })
                    .then(function (error) {
                        console.log(error);
                        deferred.reject(error);
                    });
                return deferred.promise;
            }

        }])

    .run(["$rootScope", 'jwtHelper', 'store', '$location',
        function ($rootScope, jwtHelper, store, $location) {
            $rootScope.$on('$routeChangeStart', function (event, next) {

                if (next.originalPath == '/login') {
                    $rootScope.bodyLayout = "login-layout blur-login";
                } else {
                    $rootScope.bodyLayout = "";
                }

                var token = store.get("token") || false;
                if (!token) {
                    $location.path("/login");
                    return true;
                }

                var bool = jwtHelper.isTokenExpired(token);
                if (bool === true)
                    $location.path("/login");
            });

        }])

    .factory('requestTokenInterceptor', ['store', function (store) {
        var sessionInjector = {
            request: function (config) {
                config.headers['Authorization'] = "Bearer " + store.get("token") || 0;
                return config;
            }
        };
        return sessionInjector;
    }]);
