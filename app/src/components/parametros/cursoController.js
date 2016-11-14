/**
 * Created by Victor on 14/11/2016.
 */
(function () {
    "use strict";

    angular
        .module('app')
        .controller('cursoController', cursoController);

    cursoController.$inject = ['$scope'];
    function cursoController($scope) {
        var ctrl = this;
        ctrl.endPoint = 'curso';

        ctrl.colModel = [
            {
                name: 'id',
                label: 'ID',
                editable: false,
                required: true,
                type: "number"
            },
            {
                name: 'anio',
                label: 'Año',
                editable: true,
                required: true,
                type: 'number'
            },
            {
                name: 'periodo',
                label: 'Periodo',
                editable: true,
                required: true,
                type: 'number',
                editoptions: {
                    min: "1",
                    max: "2"
                }
            }
        ];

    }
})();