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
    }
})();
