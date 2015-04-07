'use strict';

/* Controller Collections */

/*
 * 
 * Rights : 
 * 
 * {
 "Landsat": {
 "name": "Landsat",
 "group": "default",
 "rights": {
 "search": true,
 "download": false,
 "visualize": true,
 "post": false,
 "put": false,
 "delete": false,
 "filters": null
 }
 },
 ...
 }
 */


angular.module('administration').controller('CollectionsController', ['$scope', '_COLLECTIONS', 'initialization',
    function($scope, _COLLECTIONS, initialization) {

        if (initialization.ok) {

            $scope.rights = [];

            /*
             * Get rights for each collection
             */
            $scope.getRights = function() {
                _COLLECTIONS.get(function(data) {
                    $scope.rights = data;
                    $scope.busy = false;
                });
            };

            /**
             * Set right for a collection
             * 
             * @param {String} collection
             * @param {String} right
             * @param {boolean} value
             */
            $scope.setRight = function(collection, right, value) {

                if (value === 1) {
                    value = 0;
                } else if (value === 0) {
                    value = 1;
                } else {
                    alert('error - setRight');
                    return;
                }

                var options = [];
                options['emailorgroup'] = 'default';
                options['collection'] = collection;
                options['field'] = right;
                options['value'] = Number(value);

                _COLLECTIONS.setRight(options, function() {
                    $scope.getRights();
                });
            };

            /*
             * Init the context
             */
            $scope.init = function() {
                $scope.busy = true;
                $scope.getRights();
                $scope.$emit('showCollections');
            };

            $scope.init();

        }

    }]);
