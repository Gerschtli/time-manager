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

        function add(task) {
            return $http(
                {
                    method: 'POST',
                    url: '/api/task',
                    data: task,
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
