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
                    toastr.success('Loaded all Tasks');
                });
        }

        function createTask() {
            taskService.add(vm.newTask)
                .then(function(data) {
                    vm.tasks.push(data);
                    vm.newTask = {};
                    toastr.success('Created Task');
                });
        }

        function updateTask(task) {
            // TODO
            toastr.info('Update Task');
        }

        function deleteTask(task) {
            taskService.delete(task)
                .then(function(data) {
                    var index = vm.tasks.indexOf(task);
                    if (index > -1) {
                        vm.tasks.splice(index, 1);
                    }
                    toastr.success('Deleted Task');
                });
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
