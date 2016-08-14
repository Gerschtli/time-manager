(function() {
    'use strict';

    angular
        .module('timeManager')
        .filter('formatMilliSeconds', formatMilliSeconds);

    function formatMilliSeconds() {
        return formatMilliSecondsFilter;

        ////////////////

        function formatMilliSecondsFilter(milliSeconds) {
            var hours = Math.floor(milliSeconds / (60 * 60 * 1000));
            var minutes = Math.floor(milliSeconds / (60 * 1000) - (hours * 60));
            if (minutes < 10) {
                minutes = '0' + minutes;
            }
            return hours + ':' + minutes;
        }
    }

})();