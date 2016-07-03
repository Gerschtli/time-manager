(function() {
    'use strict';

    angular
        .module('timeManager')
        .factory('taskService', taskService);

    taskService.$inject = ['$http', 'toastr'];

    /* @ngInject */
    function taskService($http, toastr) {
        var service = {
            add: add,
            getAll: getAll,
        };
        return service;

        ////////////////

        function add(task, successCallback) {
            $http(
                {
                    method: 'POST',
                    url: '/api/task',
                    data: task,
                }
            ).then(
                function success(response) {
                    successWrapper(response, successCallback);
                },
                function error(response) {
                    errorWrapper(response);
                }
            );
        }

        function getAll(successCallback) {
            $http(
                {
                    method: 'GET',
                    url: '/api/task',
                }
            ).then(
                function success(response) {
                    successWrapper(response, successCallback);
                },
                function error(response) {
                    errorWrapper(response);
                }
            );
        }

        function successWrapper(response, successCallback) {
            successCallback(response.data);
        }

        function errorWrapper(response) {
            toastr.error(
                response.data.code + ' - ' + response.data.message,
                response.data.description
            );
        }
    }
})();
