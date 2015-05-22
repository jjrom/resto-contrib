(function() {

    'use strict';

    angular.module('administration')
            .filter('split', split);

    function split() {
        return function(input, splitChar, splitIndex) {
            /*
             * TODO : add tests
             */
            return input.split(splitChar)[splitIndex];
        };
    }
    ;

    angular.module('administration')
            .filter('isEmpty', function() {
                var inc;
                return function(obj) {
                    for (inc in obj) {
                        if (obj.hasOwnProperty(inc)) {
                            return false;
                        }
                    }
                    return true;
                };
            });
            
    

})();