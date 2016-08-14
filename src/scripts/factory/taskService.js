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
            update: update,
        };
        return service;

        ////////////////

        function add(task) {
            task = clearTask(task);
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

        function update(task) {
            task = clearTask(task);
            return $http(
                {
                    method: 'PUT',
                    url: '/api/task/' + task.taskId,
                    data: task,
                }
            ).then(success).catch(error);
        }

        function clearTask(task) {
            return {
                taskId: task.taskId,
                description: task.description,
                times: task.times,
            };
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
