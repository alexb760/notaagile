/**
 * Created by Victor on 7/11/2016.
 */
(function () {
    'use strict';
    angular.module("app")
        .factory("ResourceService", ResourceService);

    ResourceService.$inject = ['$resource', 'config'];

    function ResourceService($resource, config) {
        var resourceLoaded = [];

        var service = {
            getService: getService
        };

        return service;


        function getService(endPoint) {

            if (resourceLoaded[endPoint] == undefined) {

                resourceLoaded[endPoint] = $resource(config.APIURL + "/" + endPoint + "/:id", {id: ""},
                    {'update': {method: 'PUT'}}
                );

                return resourceLoaded[endPoint];
            } else {
                return resourceLoaded[endPoint];

            }
        }
    }
})();