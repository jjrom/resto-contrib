'use strict';

/* Controller Stats */

angular.module('administration').controller('StatsController', ['$scope', '_COLLECTIONS', 'initialization',
    function($scope, _COLLECTIONS, initialization) {

        if (initialization.ok) {
            $scope.stats = [];

            /*
             * Get stats for each collection
             */
            $scope.getStats = function() {
                _COLLECTIONS.stats(function(data) {
                    $scope.stats = data;
                    $scope.busy = false;
                });
            };

            /*
             * Init the context
             */
            $scope.init = function() {
                $scope.busy = true;
                $scope.getStats();
                $scope.$emit('showStats');
            };

            $scope.init();
        }
    }]);
