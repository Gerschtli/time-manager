(function() {
    'use strict';

    angular
        .module('timeManager')
        .controller('MainController', MainController);

    MainController.$inject = ['$http'];

    /* @ngInject */
    function MainController($http) {
        var vm = this;
        vm.title = 'MainController';
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
        }

        function updateTask(task) {
            // TODO
        }

        function deleteTask(task) {
            // TODO
        }

        function addTime(task, time) {
            // TODO
        }

        function deleteTime(task, time) {
            // TODO
        }
    }
})();
