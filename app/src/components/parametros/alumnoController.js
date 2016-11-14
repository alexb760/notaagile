/**
 * Created by Victor on 14/11/2016.
 */
(function () {
    "use strict";

    angular
        .module('app')
        .controller('alumnoController', alumnoController);

    alumnoController.$inject = ['$scope'];
    function alumnoController($scope) {
        var ctrl = this;
        ctrl.endPoint = 'alumno';

        ctrl.colModel = [
            {
                name: 'id',
                label: 'ID',
                editable: false,
                required: true,
                type: "number"
            },
            {
                name: 'nombres',
                label: 'Nombres',
                editable: true,
                required: true,
                type: "text",
                editoptions: {
                    size: "80",
                    maxlength: "200"
                }
            },
            {
                name: 'apellidos',
                label: 'Apellidos',
                editable: true,
                required: true,
                type: 'text',
                editoptions: {
                    size: "80",
                    maxlength: "200"
                }
            },
            {
                name: 'doc_identificacion',
                label: 'Doc. de identificación',
                editable: true,
                required: true,
                type: 'text',
                editoptions: {
                    size: "80",
                    maxlength: "200"
                }
            }
        ];

    }
})();