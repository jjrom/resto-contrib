'use strict';

/* Controller Users */

angular.module('administration').controller('HomeController', ['$scope', '_USERS', 'initialization',
    function($scope, _USERS, initialization) {

        if (initialization.ok) {
            /*
             * Init the context
             */
            $scope.init = function() {
                _USERS.stats(function(data) {
                    $scope.nb_users = data.users.count;
                    $scope.nb_downloads = data.download.count;
                    $scope.nb_search = data.search.count;
                }, function(data) {
                    alert('error - ' + data.ErrorMessage);
                });

                $scope.$emit('showHome');
            };

            $scope.init();
        }
    }]);

