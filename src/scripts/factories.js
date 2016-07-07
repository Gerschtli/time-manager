(function() {
    'use strict';

    angular
        .module('timeManager')
        .factory('taskService', taskService);

    taskService.$inject = ['$http', '$q', 'toastr'];

    /* @ngInject */
    function taskService($http, $q, toastr) {
        var service = {
            add: add,
            delete: deleteFunction,
            getAll: getAll,
        };
        return service;

        ////////////////

        function add(task) {
            return $http(
                {
                    method: 'POST',
                    url: '/api/task',
                    data: task,
                }
            ).then(success).catch(error);
        }

        function deleteFunction(task) {
            return $http(
                {
                    method: 'DELETE',
                    url: '/api/task/' + task.taskId,
                }
            ).then(success).catch(error);
        }

        function getAll() {
            return $http(
                {
                    method: 'GET',
                    url: '/api/task',
                }
            ).then(success).catch(error);
        }

        function success(response) {
            return response.data;
        }

        function error(response) {
            toastr.error(
                response.data.code + ' - ' + response.data.message,
                response.data.description
            );
            return $q.reject(response);
        }
    }
})();
