/**
 * Created by Victor on 7/11/2016.
 */
(function () {
    'use strict';

    angular
        .module('app')
        .directive('tableDirective', tableDirective);

    tableDirective.$inject = ['$timeout'];
    function tableDirective($timeout) {
        var directive = {
            restrict: 'E',
            scope: {
                id: '@',
                endPoint: '@',
                colModel: '='
            },
            link: tableDirectiveLink,
            templateUrl: 'shared/table/tableDirective.html',
            controller: tableDirectiveController,
            controllerAs: 'vm',
            bindToController: true,
            replace: true
        };

        return directive;

        function tableDirectiveLink($scope, elem, attr, vm) {
            $timeout(function () {
                vm.getData();

                vm.tablaSelector = $('#table-directive-' + vm.id).DataTable(vm.dtOptions);
                vm.modalSelector = $('#modal-table-' + vm.id);

                //Para que cada vez que se cierre el modal se resete el formulario
                vm.modalSelector.on('hidden.bs.modal', function () {
                    vm.formModal = {};
                    $scope.$apply();
                });

                $.fn.dataTable.Buttons.defaults.dom.container.className = 'dt-buttons btn-overlap btn-group btn-overlap';

                //Creación del boton de agregar
                new $.fn.dataTable.Buttons(vm.tablaSelector, {
                    buttons: [
                        {
                            "extend": "colvis",
                            "text": "<i class='ace-icon fa fa-plus-square white'></i> Agregar",
                            "className": "btn btn-primary"
                        }
                    ]
                });
                vm.tablaSelector.buttons().container().appendTo($('.tableTools-container-' + vm.id));


                //Acciones para el boton de agregar
                vm.tablaSelector.button(0).action(function (e, dt, button, config) {
                    vm.formIsEdit = false;
                    vm.modalSelector.modal("show");
                });

                //Cuando se presiona el boton de editar o eliminar
                $('#table-directive-' + vm.id + ' tbody').on('click', '.dtBtnDirEdit', function () {
                    vm.editarFrm($(this).parents('tr'));
                }).on('click', '.dtBtnDirDel', function () {
                    vm.eliminarRegistro($(this).parents('tr'))
                });
            }, 0);
        }
    }

    tableDirectiveController.$inject = ['$scope', 'ResourceService'];
    function tableDirectiveController($scope, ResourceService) {
        var vm = this;
        var dtColumns = [];
        var resource = {};

        vm.tmpTREdit = {};//Varible que guarda temporalmente la TR de la Datatable que se esta editando.
        vm.formIsEdit = false;//Flag que indica que el formulario esta en modo edicion.
        vm.formModal = {};
        vm.ctModel = [];
        vm.tablaSelector = {};
        vm.modalSelector = {};
        vm.dtOptions = {
            data: vm.ctModel,
            columns: dtColumns
        };


        //Se definen las columnas de la tabla
        $.each(vm.colModel, function ($idxCol, $itemCol) {
            dtColumns.push({data: $itemCol.name});
        });

        /**
         * Columna de accion, notese que el boton de editar tiene la clase dtBtnDirEdit y el de eliminar tiene
         * la clase dtBtnDirDel.
         * En el link se puede observar que con jQuery se le agrega unos eventos a estas clases y asi poder obtener
         * la row que se esta editando o eliminando.
         */
        dtColumns.push({
            "data": null,
            "defaultContent": '<div class="btn-group"><button class="btn btn-xs btn-info dtBtnDirEdit"><i class="ace-icon fa fa-pencil bigger-120"></i></button><button class="btn btn-danger btn-xs dtBtnDirDel" data-tipo="delete"><i class="ace-icon fa fa-trash-o bigger-120"></i></button></div>'
        });

        //Funcion que se encarga de consultar la data del endPoint
        vm.getData = function () {
            var resource = ResourceService.getService(vm.endPoint);

            resource.query(function (response) {
                $.each(response, function ($idxRes, $itemRes) {
                    vm.ctModel.push($itemRes);
                });
                vm.tablaSelector.rows.add(vm.ctModel).draw();
            }, function (error) {
                console.log(error);
                $.gritter.add({
                    title: 'Error',
                    text: 'Ocurrio un error al obtener los datos',
                    class_name: 'gritter-error'
                });
            });
        };

        //Funcion que se encarga de guardar un registro
        vm.submitFrm = function () {
            resource = ResourceService.getService(vm.endPoint);

            //Reviso si el formulario esta en modo edicion o creación
            if (vm.formIsEdit) {

                resource.update({id: vm.formModal.id}, vm.formModal, function (response) {
                    //Obtengo la tmpTREdit para actualizar su data y pasarla al Datatable que atualice la Row
                    vm.tablaSelector.row(vm.tmpTREdit).data(response).draw();
                    vm.modalSelector.modal('hide');
                    $.gritter.add({
                        title: 'Notificación',
                        text: 'Registro editado correctamente',
                        class_name: 'gritter-success'
                    });
                }, function (error) {
                    console.log(error);
                    $.gritter.add({
                        title: 'Error',
                        text: 'Ocurrio un error al actualizar los datos',
                        class_name: 'gritter-error'
                    });
                });

            } else {

                resource.save(vm.formModal, function (response) {
                    vm.tablaSelector.row.add(response).draw();
                    vm.modalSelector.modal('hide');
                    $.gritter.add({
                        title: 'Notificación',
                        text: 'Registro creado',
                        class_name: 'gritter-success'
                    });

                }, function (error) {
                    console.log(error);
                    $.gritter.add({
                        title: 'Error',
                        text: 'Ocurrio un error al guardar los datos',
                        class_name: 'gritter-error'
                    });
                });
            }
        };

        //Funcion que prepara el formulario para editar un registro
        vm.editarFrm = function (trDt) {
            vm.tmpTREdit = trDt;
            var data = vm.tablaSelector.row(vm.tmpTREdit).data();
            vm.formIsEdit = true;
            $.each(data, function ($idx, $val) {
                vm.formModal[$idx] = $val;
            });
            $scope.$apply();
            vm.modalSelector.modal("show");
        };

        //Funcion que elimina un registro
        vm.eliminarRegistro = function ($tr) {
            var data = vm.tablaSelector.row($tr).data();
            resource = ResourceService.getService(vm.endPoint);

            resource.delete({id: data.id},
                function ($response) {
                    //Eliminando la row de la Datatable
                    vm.tablaSelector.row($tr).remove().draw();
                    $.gritter.add({
                        title: 'Notificación',
                        text: 'Registro eliminado',
                        class_name: 'gritter-success'
                    });

                }, function ($error) {
                    console.log($error);
                    $.gritter.add({
                        title: 'Error',
                        text: 'Ocurrio un error al eliminar el registro',
                        class_name: 'gritter-error'
                    });
                });
        }
    }
})();