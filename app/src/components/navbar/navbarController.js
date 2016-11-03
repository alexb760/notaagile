/**
 * Created by Victor on 17/10/2016.
 */
(function () {
    "use strict";

    angular
        .module('app')
        .controller('navbarController', navbarController);

    navbarController.$inject = ['$scope', 'config', '$localStorage', '$q', '$http', '$state', '$auth'];

    function navbarController($scope, config, $localStorage, $q, $http, $state, $auth) {
        var ctrl = this;
        ctrl.$storage = $localStorage;
        //los mandamos a la vista como user
        ctrl.name = ctrl.$storage.name;

        ctrl.logout = function () {
            var deferred;
            deferred = $q.defer();
            $http({
                method: 'GET',
                url: config.APIURL + '/logout'
            })
                .then(function (res) {
                    ctrl.$storage.$reset();
                    $auth.logout();
                    deferred.resolve(res);
                    $state.go("login");

                }, function (error) {
                    console.log(error);
                    deferred.reject(error);
                });
            return deferred.promise;
        };
    }
})();