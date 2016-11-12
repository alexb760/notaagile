/**
 * Created by Victor on 2/11/2016.
 */
(function () {
    "use strict";

    angular
        .module('app')
        .controller('asignaturaController', asignaturaController);

    asignaturaController.$inject = ['$scope'];
    function asignaturaController($scope) {
        var ctrl = this;
        ctrl.endPoint = 'asignatura';

        ctrl.colModel = [
            {
                name: 'id',
                label: 'ID',
                editable: false,
                required: true,
                type: "number"
            },
            {
                name: 'name',
                label: 'Nombre',
                editable: true,
                required: true,
                type: 'text',
                editoptions: {
                    size: "80",
                    maxlength: "100"
                }
            }
        ];

    }
})();