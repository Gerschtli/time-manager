(function() {
    'use strict';

    angular
        .module('timeManager')
        .factory('timeCalculator', timeCalculator);

    timeCalculator.$inject = [];

    /* @ngInject */
    function timeCalculator() {
        var service = {
            calculatePerTask: calculatePerTask
        };
        return service;

        ////////////////

        function calculatePerTask(task) {
            var calc = 0;
            for (var i = 0; i < task.times.length; i++) {
                calc += calculate(task.times[i]);
            }
            return calc;
        }

        function calculate(time) {
            if (! time instanceof Object || time.start === undefined || time.end === undefined) {
                return 0;
            }
            var start = new Date(time.start);
            var end = new Date(time.end);
            return Math.abs(end.getTime() - start.getTime());
        }
    }
})();
