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
    }

})();