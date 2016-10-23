/**
 * Created by Victor on 17/10/2016.
 */
(function () {
    "use strict";

    angular
        .module('app')
        .controller('homeController', homeController);

    homeController.$inject = ['$scope', 'config', '$localStorage', '$q', '$http', '$location'];
    function homeController($scope, config, $localStorage, $q, $http, $location) {
        var ctrl = this;
        ctrl.$storage = $localStorage;
        //los mandamos a la vista como user
        ctrl.name = ctrl.$storage.name;
        ctrl.email = ctrl.$storage.email;
        ctrl.menu = ctrl.$storage.menu;

        ctrl.logout = function () {
            var deferred;
            deferred = $q.defer();
            $http({
                method: 'GET',
                url: config.APIURL + '/logout'
            })
                .then(function (res) {
                    deferred.resolve(res);
                    ctrl.$storage.$reset();
                    $location.path("/login");

                },function (error) {
                    console.log(error);
                    deferred.reject(error);
                });
            return deferred.promise;
        };
    }
})();