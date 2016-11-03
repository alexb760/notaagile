/**
 * Created by Victor on 17/10/2016.
 */
(function () {
    "use strict";

    angular
        .module('app')
        .controller('loginController', loginController);

    loginController.$inject = ['$scope', 'config', '$auth', '$state', '$localStorage'];

    function loginController($scope, config, $auth, $state, $localStorage) {
        var ctrl = this;
        ctrl.$storage = $localStorage;
        ctrl.login = function (user) {
            $auth.login(user).then(function (res) {
                if (res.data) {
                    ctrl.$storage.name = res.data.name;
                    ctrl.$storage.email = res.data.email;
                    ctrl.$storage.menu = res.data.menu;
                    $state.go("app.home");
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