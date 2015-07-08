(function() {

    'use strict';

    /*
     * Copyright 2014 Jérôme Gasperi
     *
     * Licensed under the Apache License, version 2.0 (the "License");
     * You may not use this file except in compliance with the License.
     * You may obtain a copy of the License at:
     *
     *   http://www.apache.org/licenses/LICENSE-2.0
     *
     * Unless required by applicable law or agreed to in writing, software
     * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
     * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
     * License for the specific language governing permissions and limitations
     * under the License.
     */
    /* 
     Author     : remi.mourembles@capgemini.com
     */

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


    angular.module('administration').controller('CollectionsController', ['$scope', '$filter', 'administrationServices', 'administrationAPI', collectionsController]);

    function collectionsController($scope, $filter, administrationServices, administrationAPI) {

        if (administrationServices.isUserAnAdministrator()) {
            $scope.rights = [];

            /*
             * Get rights for each collection
             */
            $scope.getRights = function() {
                administrationAPI.getCollections(function(data) {
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
                    alert($filter('translate')('error.setRight'));
                    return;
                }

                var options = [];
                options['emailorgroup'] = 'default';
                options['collection'] = collection;
                options['field'] = right;
                options['value'] = Number(value);

                administrationAPI.setCollectionRight(options, function() {
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
    }
    ;
})();