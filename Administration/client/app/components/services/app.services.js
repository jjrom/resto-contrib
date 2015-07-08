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
     Created on : 4 mars 2015, 10:27:08
     Author     : remi.mourembles@capgemini.com
     */

    /* Services */

    var services = angular.module('services', []);

    services.factory('userActivation', ['$http', 'CONFIG',
        function($http, CONFIG) {
            return {
                get: function(callback, error) {
                    $http({
                        method: 'POST',
                        url: CONFIG.activationURL,
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                        transformRequest: function(obj) {
                            var str = [];
                            for (var p in obj)
                                str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                            return str.join("&");
                        },
                        data: {
                            emailorgroup: 'default',
                            collection: collection,
                            field: right,
                            value: value
                        }
                    }).success(function(data) {
                        if (data.ErrorMessage) {
                            error(data);
                        } else {
                            callback(data);
                        }
                    }).error(function() {
                        error('error - user activation');
                    });
                }
            };
        }]);


    services.factory('_RIGHTS', ['$http', 'CONFIG',
        function($http, CONFIG) {
            return {
                
            };
        }]);
})();