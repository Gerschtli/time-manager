(function() {
    'use strict';

    angular
        .module('timeManager')
        .controller('MainController', MainController);

    MainController.$inject = ['$http', 'toastr', 'taskService'];

    /* @ngInject */
    function MainController($http, toastr, taskService) {
        var vm = this;
        vm.tasks = [];
        vm.newTask = {};
        vm.createTask = createTask;
        vm.updateTask = updateTask;
        vm.deleteTask = deleteTask;
        vm.deleteTime = deleteTime;
        vm.addTime = addTime;

        activate();

        ////////////////

        function activate() {
            getAllTasks();
        }

        function getAllTasks() {
            taskService.getAll()
                .then(function(data) {
                    vm.tasks = data;
                });
        }

        function createTask() {
            taskService.add(vm.newTask)
                .then(function(data) {
                    vm.tasks.push(data);
                    vm.newTask = {};
                });
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
