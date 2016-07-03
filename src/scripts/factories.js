(function() {
    'use strict';

    angular
        .module('timeManager')
        .factory('taskService', taskService);

    taskService.$inject = ['$http', 'toastr'];

    /* @ngInject */
    function taskService($http, toastr) {
        var service = {
            getAll: getAll
        };
        return service;

        ////////////////

        function getAll(successCallback, errorCallback) {
            $http(
                {
                    method: 'GET',
                    url: '/api/task'
                }
            ).then(
                function success(response) {
                    successCallback(response.data);
                },
                function error(response) {
                    toastr.error('Data could not be fetched', response.status + ' - ' + response.statusText);
                    if (typeof errorCallback === 'function') {
                        errorCallback(response);
                    }
                }
            );
        }
    }
})();
