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

    angular.module('administration')
            .factory('restoUsersAPI', ['$http', 'CONFIG', restoUsersAPI]);

    function restoUsersAPI($http, config) {

        var api = {
            checkLicense: checkLicense,
            connect: connect,
            getOrder: getOrder,
            getOrders: getOrders,
            postOrder: postOrder,
            signLicense: signLicense,
            signup: signup
        };

        return api;

        /////////

        /**
         * Connect - get a new token
         * 
         * @param {type} callback
         * @param {type} error
         * @returns {undefined}
         */
        function connect(callback, error) {
            /*
             * Call RESTo api
             */
            $http({
                method: 'GET',
                url: config.restoServerUrl + 'api/users/connect'
            }).success(function(data) {
                if (data.token) {
                    callback(data.token);
                } else {
                    error();
                }
            }).error(function() {
                error();
            });

        }
        ;

        /**
         * Check if user has signed license
         * 
         * @param {type} options
         *          {string} : userid
         *          {string} : collection
         * @param {type} callback
         * @param {type} error
         * @returns {undefined}
         */
        function checkLicense(options, callback, error) {

            $http({
                method: 'GET',
                url: config.restoServerUrl + '/users/' + options.userid + '/signatures/' + options.collection,
                dataType: "json",
                contentType: 'application/json'
            }).success(function(data) {
                if (data.ErrorMessage) {
                    alert("internal error : " + data.ErrorMessage);
                } else {
                    /*
                     * resto return TRUE if license has to be signed,
                     * FALSE if license is signed.
                     */
                    if (data.signatures[options.collection] === false) {
                        callback();
                    } else {
                        error('unsigned');
                    }
                }
            }).error(function(data) {
                alert("internal error : " + data.ErrorMessage);
            });
        }
        ;

        /**
         * Sign license
         * 
         * @param {type} options
         *          {string} : userid
         *          {string} : collection
         * @param {type} callback
         * @param {type} error
         * @returns {Boolean}
         */
        function signLicense(options, callback, error) {

            if (!options.userid || !options.collection) {
                alert('internal error - signLicense');
                return false;
            }

            $http({
                method: 'POST',
                url: config.restoServerUrl + 'api/users/' + options.userid + '/signLicense',
                dataType: "json",
                data: {
                    collection: options.collection
                },
                contentType: 'application/json'
            }).success(function(data) {
                if (data.ErrorMessage) {
                    error(data);
                } else {
                    callback();
                }
            }).error(function(data) {
                if (data.ErrorMessage) {
                    error(data.ErrorMessage);
                } else {
                    error(data);
                }
            });
        }
        ;


        /*
         * Get order 
         * 
         * @param {object} options
         * @param {callback} success
         * @param {callback} error
         * @returns {undefined}
         */
        function getOrder(options, success, error) {

            /*
             * Get orders
             */
            $http({
                method: 'GET',
                url: config.restoServerUrl + 'users/' + options.userid + '/orders/' + options.orderid + '.meta4',
                dataType: "json",
                contentType: 'application/json'
            }).success(function(data) {
                if (data.ErrorMessage) {
                    error(data);
                } else {
                    success(data);
                }
            }).error(function(data) {
                if (data.ErrorMessage) {
                    error(data.ErrorMessage);
                } else {
                    error(data);
                }
            });
        }
        ;

        /*
         * Post order
         * 
         * @param {object} options
         * @param {callback} success
         * @param {callback} error
         * @returns {undefined}
         */
        function postOrder(options, success, error) {
            /*
             * Post orders
             */
            $http({
                method: 'POST',
                url: config.restoServerUrl + 'users/' + options.userid + '/orders',
                dataType: "json",
                data: options.features,
                contentType: 'application/json'
            }).success(function(data) {
                success(data);
            }).error(function() {
                error();
            });
        }
        ;

        /*
         * Get orders 
         * 
         * @param {object} options
         * @param {callback} success
         * @param {callback} error
         * @returns {undefined}
         */
        function getOrders(options, success, error) {
            /*
             * Get orders
             */
            $http({
                method: 'GET',
                url: config.restoServerUrl + 'users/' + options.userid + '/orders'
            }).success(function(data) {
                success(data.orders);
            }).error(function(data) {
                error(data);
            });
        }
        ;


        /**
         * Signup : add user to resto database
         * 
         * @param {array} options
         * @param {function} callback
         * @param {function} error
         * @returns {undefined}
         */
        function signup(options, callback, error) {
            $http({
                method: 'POST',
                url: config.restoServerUrl + '/users',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                data: {
                    email: options['email'],
                    password: options['password'],
                    username: options['username'],
                    givename: options['givename'],
                    lastname: options['lastname']
                }
            }).success(function(data) {
                if (data.ErrorMessage) {
                    error(data);
                } else {
                    callback(data);
                }
            }).error(function() {
                alert('error - user creation');
            });
        }
        ;

    }
    ;

})();
