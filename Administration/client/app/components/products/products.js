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
     Created on : 01 juillet 2015
     Author     : remi.mourembles@capgemini.com
     */

    /* Controller Products */

    angular.module('administration').controller('ProductsController', ['$scope', 'administrationServices', 'administrationAPI', 'CONFIG', productsController]);

    function productsController($scope, administrationServices, administrationAPI, CONFIG) {

        if (administrationServices.isUserAnAdministrator()) {

            $scope.collections = [];

            /**
             * Get products
             * 
             * If concatData is true, data is concataning with existing data. If not,
             * data is replacing existing data.
             * 
             * @param {boolean} concatData
             */
            $scope.getProducts = function(concatData) {

                var options = [];
                options['identifier'] = $scope.identifier;
                options['page'] = $scope.page;
                if ($scope.collection) {
                    options['collection'] = $scope.collection;
                }

                administrationAPI.searchProducts(options, function(data) {

                    if (concatData === false) {
                        $scope.products = data.features;
                    } else {
                        $scope.products = $scope.products.concat(data.features);
                    }
                    $scope.showProducts = true;

                    /*
                     * If no more products, deacrease page counter
                     */
                    if (!data.features[0]) {
                        $scope.page = $scope.page - 1;
                    }

                }, function() {
                    $scope.page = $scope.page - 1;
                    alert($filter('translate')('error.getProducts'));
                });
            };

            /*
             * Call by infinite scroll
             */
            $scope.loadMore = function() {
                $scope.page = $scope.page + 1;
                $scope.getProducts(true);
            };

            $scope.getCollections = function() {
                administrationAPI.getCollections(function(data) {
                    for (var c in data) {
                        $scope.collections.push(c);
                    }
                }, function() {
                    alert($filter('translate')('error.setCollections'));
                });
            };

            $scope.updateVisibility = function(featureid, collection, visibility) {

                var options = [];
                options['featureid'] = featureid;
                options['collection'] = collection;
                options['visibility'] = visibility;

                administrationAPI.putGrantedVisibility(options,
                        function() {
                            $scope.setParam('identifier', featureid);
                        }, function() {
                    alert($filter('translate')('error.updateVisibility'));
                });
            };

            $scope.setParam = function(type, value) {
                $scope.init();

                if (type === 'identifier') {
                    $scope.identifier = value;
                } else if (type === 'collection') {
                    $scope.collection = value;
                }

                $scope.getProducts(false);
            };

            $scope.resetFilters = function() {
                $scope.init();
                $scope.initFilters();
                $scope.getProducts(false);
            };

            /*
             * Init the context
             */
            $scope.init = function() {
                $scope.products = [];
                $scope.page = 1;
                $scope.showHistory = false;
            };

            $scope.initFilters = function() {
                $scope.selectedCollection = null;
                $scope.collection = null;
                $scope.keyword = null;
                $scope.identifier = null;
            };

            $scope.init();
            $scope.getProducts();
            $scope.getCollections();
            $scope.$emit('showProducts');
        }
    }
    ;
})();
