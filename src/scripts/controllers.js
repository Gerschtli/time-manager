(function() {
    'use strict';

    angular
        .module('timeManager')
        .controller('MainController', MainController);

    MainController.$inject = ['$http', 'toastr'];

    /* @ngInject */
    function MainController($http, toastr) {
        var vm = this;
        vm.tasks = [];
        vm.createTask = createTask;
        vm.updateTask = updateTask;
        vm.deleteTask = deleteTask;
        vm.deleteTime = deleteTime;
        vm.addTime = addTime;

        activate();

        ////////////////

        function activate() {
            $http(
                {
                    method: 'GET',
                    url: '/api/task'
                }
            ).then(
                function success(response) {
                    vm.tasks = response.data;
                },
                function error(response) {
                    console.log('error');
                }
            );
        }

        function createTask(task) {
            // TODO
            toastr.info('Create Task');
        }

        function updateTask(task) {
            // TODO
            toastr.info('Update Task');
        }

        function deleteTask(task) {
            // TODO
            toastr.info('Delete Task');
        }

        function addTime(task, time) {
            // TODO
            toastr.info('Add Time');
        }

        function deleteTime(task, time) {
            // TODO
            toastr.info('Delete Time');
        }
    }
})();
