/**
 * Created by Victor on 17/10/2016.
 */
(function () {
    "use strict";

    angular
        .module('app')
        .controller('loginController', loginController);

    loginController.$inject = ['$scope', 'config', '$auth', '$location', '$localStorage'];

    function loginController($scope, config, $auth, $location, $localStorage) {
        var ctrl = this;
        ctrl.$storage = $localStorage;
        ctrl.login = function (user) {
            console.log(user);
            $auth.login(user).then(function (res) {
                if (res.data) {
                    ctrl.$storage.name = res.data.name;
                    ctrl.$storage.email = res.data.email;
                    ctrl.$storage.menu = res.data.menu;
                    $location.path("/home");
                }
            }, function (res) {
                console.log(res);
                $.each(res.data, function (idx, val) {
                    toastr.error(val);
                });
            });
        }
    }
})();