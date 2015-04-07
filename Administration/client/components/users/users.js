'use strict';

/* Controller Users */

/*
 * users : 
 * 
 * [
 {
 "userid": "74",
 "email": "user_0",
 "groupname": "user_0",
 "username": "user_0",
 "givenname": null,
 "lastname": null,
 "registrationdate": "2014-12-03T14:25:54Z",
 "activated": true,
 "lastsessionid": null
 },
 ...
 ]
 */

angular.module('administration').controller('UsersController', ['$scope', '$location', '_USERS', 'initialization', 'CONFIG',
    function($scope, $location, _USERS, initialization, CONFIG) {

        if (initialization.ok) {
            /*
             * Get users
             */
            $scope.getUsers = function(concat) {

                /*
                 * if no concat, reset start index
                 */
                if (!concat) {
                    $scope.initCounter();
                }

                /*
                 * Options to create loading url
                 * @type Array
                 */
                var options = [];
                options['startIndex'] = $scope.startIndex;
                options['offset'] = $scope.offset;

                /*
                 * Check if a keyword is written in the search bar
                 */
                if ($scope.keyword && $scope.keyword !== '') {
                    options['keyword'] = $scope.keyword;
                }

                /*
                 * Get results 
                 */
                _USERS.get(options, function(data) {

                    if (data.ErrorMessage) {
                        alert('error - ' + data.ErrorMessage);
                    } else {
                        if (concat) {
                            $scope.users = $scope.users.concat(data);
                        } else {
                            $scope.users = data;
                        }

                        /*
                         * increment start index
                         */
                        $scope.startIndex = $scope.startIndex + $scope.offset;

                        /*
                         * show table of results
                         */
                        $scope.showUsers = true;
                        $scope.busy = false;

                        /*
                         * At the end of data, stop infinitscrolling with busy attribute
                         */
                        if (!data[0]) {
                            $scope.busy = true;
                            $scope.startIndex = $scope.startIndex - $scope.offset;
                        }
                    }


                });
            };

            /**
             * Select user
             * 
             * @param {String} user
             * 
             */
            $scope.selectUser = function(user) {
                $scope.init();
                $scope.selectedUser = user;
                $location.path($location.path() + '/' + user.userid);
            };

            /*
             * Called by infinite scroll
             */
            $scope.loadMore = function() {
                if ($scope.busy)
                    return;
                $scope.busy = true;
                $scope.getUsers(true);
            };

            /*
             * init the context
             */
            $scope.init = function() {
                $scope.users = [];
                $scope.busy = true;
                $scope.showUsers = false;
                $scope.selectedUser = null;
                $scope.keyword = null;

                $scope.initCounter();
                $scope.$emit('showUsers');
            };

            /*
             * init counter startindex and offset
             */
            $scope.initCounter = function() {
                $scope.startIndex = 0;
                $scope.offset = CONFIG.offset;
            };

            $scope.init();
            $scope.getUsers(false);
        }
    }]);