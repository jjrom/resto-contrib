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
     Created on : 06 mai 2015, 10:27:08
     Author     : remi.mourembles@capgemini.com
     */

    angular.module('administration')
            .factory('administrationAPI', ['$http', 'CONFIG', administrationAPI]);
    function administrationAPI($http, config) {

        var api = {
            activateUser: activateUser,
            addUser: addUser,
            deactivateUser: deactivateUser,
            deleteRight: deleteRight,
            getCollections: getCollections,
            getCollectionsStats: getCollectionsStats,
            getHistory: getHistory,
            getRight: getRight,
            getSignatures: getSignatures,
            getUser: getUser,
            getUsers: getUsers,
            getUsersStats: getUsersStats,
            setAdvancedRight: setAdvancedRight,
            setCollectionRight: setCollectionRight,
            setRight: setRight,
            setUserGroup: setUserGroup
        };
        return api;
        /////////

        /**
         * Get history 
         * 
         * @param {array} options
         * @param {function} callback
         * @param {function} error
         * @returns {undefined}
         */
        function getHistory(options, callback, error) {

            var startindex = options['startindex'];
            var offset = options['offset'];
            var ascordesc = options['ascordesc'];
            var orderby = options['orderby'];
            var userid = options['userid'];
            var method = options['method'];
            var service = options['service'];
            var maxDate = options['maxDate'];
            var minDate = options['minDate'];
            var collection = options['collection'];

            var url = config.restoServerUrl + config.administrationEndpoint + '/users/';
            if (userid) {
                url = url + userid + '/';
            }


            url = url + 'history.json?startIndex=' + startindex + '&numberOfResults=' + offset;

            if (ascordesc) {
                url = url + "&ascordesc=" + ascordesc;
            }
            if (orderby) {
                url = url + "&orderBy=" + orderby;
            }
            if (method) {
                url = url + "&method=" + method;
            }
            if (service) {
                url = url + "&service=" + service;
            }
            if (maxDate) {
                url = url + "&maxDate=" + maxDate;
            }
            if (minDate) {
                url = url + "&minDate=" + minDate;
            }
            if (collection) {
                url = url + "&collection=" + collection;
            }


            $http.get(url)
                    .success(function(data) {
                        if (data.ErrorMessage) {
                            error(data);
                        } else {
                            callback(data);
                        }
                    })
                    .error(function() {
                        alert('error - get history');
                    });
        }
        ;

        /**
         * Get list of collections
         * 
         * @param {function} callback
         * @param {function} error
         * @returns {undefined}
         */
        function getCollections(callback, error) {
            // /administration/collections.json

            $http({
                method: 'GET',
                url: config.restoServerUrl + config.administrationEndpoint + '/collections.json'
            }).success(function(data) {
                if (data.ErrorMessage) {
                    error(data);
                } else {
                    callback(data);
                }
            }).error(function() {
                alert('error - get collections failed');
            });
        }
        ;

        /**
         * Set a right for specified collection
         * 
         * @param {array} options
         * @param {function} callback
         * @param {function} error
         * @returns {undefined}
         */
        function setCollectionRight(options, callback, error) {

            var emailorgroup = options['emailorgroup'];
            var collection = options['collection'];
            var field = options['field'];
            var value = options['value'];

            $http({
                method: 'POST',
                // /administration/collections
                url: config.restoServerUrl + config.administrationEndpoint + '/collections',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                transformRequest: function(obj) {
                    var str = [];
                    for (var p in obj)
                        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                    return str.join("&");
                },
                data: {
                    emailorgroup: emailorgroup,
                    collection: collection,
                    field: field,
                    value: value
                }
            }).success(function(data) {
                if (data.ErrorMessage) {
                    error(data);
                } else {
                    callback(data);
                }
            }).error(function() {
                alert('error - set collection right');
            });
        }
        ;

        /**
         * Get stats
         * 
         * @param {function} callback
         * @param {function} error
         * @returns {undefined}
         */
        function getCollectionsStats(callback, error) {
            // /administration/stats/collections.json
            var url = config.restoServerUrl + config.administrationEndpoint + '/stats/collections.json';


            $http({
                method: 'GET',
                url: url
            }).success(function(data) {
                if (data.ErrorMessage) {
                    error(data);
                } else {
                    callback(data);
                }
            }).error(function() {
                alert('error - get stats failed');
            });
        }
        ;

        /**
         * Get users list
         * 
         * @param {array} options
         * @param {function} callback
         * @param {function} error
         * @returns {undefined}
         */
        function getUsers(options, callback, error) {

            var startIndex = options['startIndex'];
            var offset = options['offset'];
            var keyword = options['keyword'];

            var url = config.restoServerUrl + config.administrationEndpoint + '/users?min=' + startIndex + '&number=' + offset;
            if (keyword) {
                url = url + '&keyword=' + keyword;
            }

            $http({
                method: 'GET',
                url: url
            }).success(function(data) {
                if (data.ErrorMessage) {
                    error(data);
                } else {
                    callback(data);
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

        /**
         * Get users stats
         * 
         * @param {function} callback
         * @param {function} error
         * @returns {undefined}
         */
        function getUsersStats(callback, error) {
            // administration/stats/users.json
            var url = config.restoServerUrl + config.administrationEndpoint + '/stats/users.json';
            ;

            $http.get(url)
                    .success(
                            function(data) {
                                if (data.ErrorMessage) {
                                    error(data);
                                } else {
                                    callback(data);
                                }
                            })
                    .error(function() {
                        alert('error - get stats');
                    });
        }
        ;

        /**
         * Add new user
         * 
         * @param {array} options
         * @param {function} callback
         * @param {function} error
         * @returns {undefined}
         */
        function addUser(options, callback, error) {

            $http({
                method: 'POST',
                url: config.restoServerUrl + config.administrationEndpoint + '/users',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                transformRequest: function(obj) {
                    var str = [];
                    for (var p in obj)
                        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                    return str.join("&");
                },
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
            }).error(function(e) {
                callback(e);
            });
        }
        ;

        /**
         * Get user profile
         * 
         * @param {integer} userid
         * @param {function} callback
         * @param {function} error
         * @returns {undefined}
         */
        function getUser(userid, callback, error) {

            $http.get(config.restoServerUrl + '/users/' + userid + '.json')
                    .success(
                            function(data) {
                                if (data.ErrorMessage) {
                                    error(data);
                                } else {
                                    callback(data.profile);
                                }
                            })
                    .error(function() {
                        alert('error - get user');
                    });
        }
        ;

        /**
         * Activate user
         * 
         * @param {integer} userid
         * @param {function} callback
         * @param {function} error
         * @returns {undefined}
         */
        function activateUser(userid, callback, error) {
            $http({
                method: 'POST',
                url: config.restoServerUrl + config.administrationEndpoint + "/users/" + userid + "/activate"
            }).success(function(data) {
                if (data.ErrorMessage) {
                    error(data);
                } else {
                    callback(data);
                }
            }).error(function() {
                alert('error - activate user');
            });
        }
        ;

        /**
         * Deactivate user
         * 
         * @param {integer} userid
         * @param {function} callback
         * @param {function} error
         * @returns {undefined}
         */
        function deactivateUser(userid, callback, error) {
            $http({
                method: 'POST',
                url: config.restoServerUrl + config.administrationEndpoint + "/users/" + userid + "/deactivate"
            }).success(function(data) {
                if (data.ErrorMessage) {
                    error(data);
                } else {
                    callback(data);
                }
            }).error(function() {
                alert('error - deactivate user');
            });
        }
        ;

        /**
         * Set user group
         * 
         * @param {array} options
         * @param {function} callback
         * @param {function} error
         * @returns {undefined}
         */
        function setUserGroup(options, callback, error) {

            var userid = options['userid'];
            var email = options['email'];
            var groupname = options['groupname'];

            $http({
                method: 'POST',
                url: config.restoServerUrl + config.administrationEndpoint + '/users/' + userid,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                transformRequest: function(obj) {
                    var str = [];
                    for (var p in obj)
                        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                    return str.join("&");
                },
                data: {
                    email: email,
                    groupname: groupname
                }
            }).success(function(data) {
                if (data.ErrorMessage) {
                    error(data);
                } else {
                    callback(data);
                }
            }).error(function() {
                alert('error - set group');
            });
        }
        ;

        /**
         * Get signatures
         * 
         * @param {integer} userid
         * @param {function} callback
         * @param {function} error
         * @returns {undefined}
         */
        function getSignatures(userid, callback, error) {
            $http.get(config.restoServerUrl + '/users/' + userid + '/signatures.json')
                    .success(
                            function(data) {
                                if (data.ErrorMessage) {
                                    error(data);
                                } else {
                                    callback(data.signatures);
                                }
                            })
                    .error(function() {
                        alert('error - get signatures');
                    });
        }
        ;


        function getRight(userid, callback, error) {
            $http.get(config.restoServerUrl + config.administrationEndpoint + '/users/' + userid + '/rights.json')
                    .success(
                            function(data, status, headers, config) {
                                if (data.ErrorMessage) {
                                    error(data);
                                } else {
                                    callback(data.rights);
                                }
                            })
                    .error(function() {
                        alert('error - get rights');
                    });
        }
        ;

        function deleteRight(options, callback, error) {


            var params = [];

            params['collection'] = options['collection'];
            if (options['featureid']) {
                params['featureid'] = options['featureid'];
            }
            params['userid'] = options['userid'];
            params['emailorgroup'] = options['email'];

            $http({
                method: 'POST',
                url: config.restoServerUrl + config.administrationEndpoint + '/users/' + params['userid'] + "/rights/delete",
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                transformRequest: function(obj) {
                    var str = [];
                    for (var p in obj)
                        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                    return str.join("&");
                },
                data: params
            }).success(function(data) {
                if (data.ErrorMessage) {
                    error(data.ErrorMessage);
                } else {
                    callback(data);
                }
            }).error(function() {
                alert('error - delete right');
            });
        }
        ;

        function setRight(options, callback, error) {

            var userid = options['userid'];
            var email = options['emailorgroup'];
            var collection = options['collection'];
            var field = options['field'];
            var value = options['value'];

            $http({
                method: 'POST',
                url: config.restoServerUrl + config.administrationEndpoint + '/users/' + userid + '/rights/update',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                transformRequest: function(obj) {
                    var str = [];
                    for (var p in obj)
                        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                    return str.join("&");
                },
                data: {
                    emailorgroup: email,
                    collection: collection,
                    field: field,
                    value: value
                }
            }).success(function(data) {
                if (data.ErrorMessage) {
                    error(data);
                } else {
                    callback(data);
                }
            }).error(function() {
                alert('error - set right');
            });
        }
        ;

        function setAdvancedRight(options, callback, error) {

            var userid = options['userid'];
            var emailorgroup = options['emailorgroup'];
            var collection = options['collection'];
            var featureid = options['featureid'];
            var search = options['search'];
            var visualize = options['visualize'];
            var download = options['download'];
            var canput = options['canput'];
            var canpost = options['canpost'];
            var candelete = options['candelete'];
            var filters = options['filters'];

            $http({
                method: 'POST',
                url: config.restoServerUrl + config.administrationEndpoint + '/users/' + userid + '/rights',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                transformRequest: function(obj) {
                    var str = [];
                    for (var p in obj)
                        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                    return str.join("&");
                },
                data: {
                    emailorgroup: emailorgroup,
                    collection: collection,
                    featureid: featureid,
                    search: search,
                    visualize: visualize,
                    download: download,
                    canput: canput,
                    canpost: canpost,
                    candelete: candelete,
                    filters: filters
                }
            }).success(function(data) {
                if (data.ErrorMessage) {
                    error(data);
                } else {
                    callback(data);
                }
            }).error(function(data) {
                if (data.ErrorMessage) {
                    error(data);
                } else {
                    alert('error - set advanced right');
                } 
            });
        }
        ;
    }
    ;
})();
