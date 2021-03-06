(function() {
    'use strict';

    angular
        .module('timeManager')
        .controller('MainController', MainController);

    MainController.$inject = ['$http', 'toastr', 'taskService', 'timeCalculator'];

    /* @ngInject */
    function MainController($http, toastr, taskService, timeCalculator) {
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
                    for (var i = 0; i < vm.tasks.length; i++) {
                        calculateTime(vm.tasks[i]);
                    }
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
            taskService.update(task)
                .then(function(data) {
                    var index = getTaskIndex(task);
                    vm.tasks[index] = data;
                    calculateTime(data);
                    toastr.success('Updated Task');
                });
        }

        function deleteTask(task) {
            taskService.delete(task)
                .then(function(data) {
                    var index = getTaskIndex(task);
                    if (index > -1) {
                        vm.tasks.splice(index, 1);
                    }
                    toastr.success('Deleted Task');
                });
        }

        function addTime(task, time) {
            task.times.push(time);
            task.newTime = {};
            updateTask(task);
        }

        function deleteTime(task, time) {
            var index = task.times.indexOf(time);
            if (index > -1) {
                task.times.splice(index, 1);
            }
            updateTask(task);
        }

        function calculateTime(task) {
            task.calculatedTime = timeCalculator.calculatePerTask(task);
        }

        function getTaskIndex(task) {
            return vm.tasks.indexOf(task);
        }
    }
})();
