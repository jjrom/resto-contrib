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

    angular.module('administration')
            .controller('mainController', ['$scope', 'CONFIG', '$auth', '$filter', 'administrationServices', 'restoUsersAPI', mainController]);

    function mainController($scope, CONFIG, $auth, $filter, administrationServices, restoUsersAPI) {
        
        $scope.profile = null;

        $scope.start = function() {
            $scope.init();
            
            if (administrationServices.isUserAnAdministrator()) {
                $scope.profile = $auth.getPayload();
                $scope.startRefreshToken();
            }else{
                $scope.logout();
            }
        };

        /*
         * Start connect refresh every 1000ms
         * 
         * @returns {undefined}
         */
        $scope.startRefreshToken = function() {

            if ($auth.isAuthenticated()) {
                /*
                 * Get token duration validity
                 */
                var tokenDuration = ($auth.getPayload().exp - $auth.getPayload().iat) * 700;

                setTimeout(
                        function() {
                            if ($auth.isAuthenticated()) {
                                $scope.refreshToken();
                            } else {
                                $scope.logout();
                            }

                            return true;
                        }
                , tokenDuration);
            }

        };

        /*
         * Refresh token
         * 
         * @returns {undefined}
         */
        $scope.refreshToken = function() {
            /*
             * call resto api
             */
            restoUsersAPI.connect(function(data) {
                /*
                 * Set new token
                 */
                $auth.setToken(data, true);
                $scope.startRefreshToken();

            }, function() {
                /*
                 * error => logout
                 */
                $scope.logout();
                alert($filter('translate')('alert.disconnect'));
            });
        };


        /*
         * Events to drive left panel
         */
        $scope.$on('showUser', function() {
            $scope.init();
            $scope.showUser = true;
        });
        $scope.$on('showHistory', function() {
            $scope.init();
            $scope.showHistory = true;
        });
        $scope.$on('showUsers', function() {
            $scope.init();
            $scope.showUsers = true;
        });
        $scope.$on('showHome', function() {
            $scope.init();
            $scope.showHome = true;
        });
        $scope.$on('showCollections', function() {
            $scope.init();
            $scope.showCollections = true;
        });
        $scope.$on('showStats', function() {
            $scope.init();
            $scope.showStats = true;
        });
        $scope.$on('showProducts', function() {
            $scope.init();
            $scope.showProducts = true;
        });

        /*
         * Initialize the context
         */
        $scope.init = function() {
            $scope.showUsers = false;
            $scope.showHistory = false;
            $scope.showUser = false;
            $scope.showHome = false;
            $scope.showCollections = false;
            $scope.showStats = false;
            $scope.selectedUser = null;
            $scope.showLeftMenu = false;
            $scope.showProducts = false;
            
            $scope.displayLocalAuth = CONFIG.displayLocalAuth;
        };

        /**
         * Login (submit button in signin popup)
         * 
         * @param {String} email
         * @param {String} password
         * @returns {undefined}
         */
        $scope.login = function(email, password) {
            /*
             * Call satellizer api
             */
            $auth.login({
                email: email,
                password: password
            }).then(function() {

                if (!administrationServices.isUserAnAdministrator()) {
                    /*
                     * if user is not an administrator
                     */
                    alert($filter('translate')('Sorry... You are not an administrator'));
                    $scope.logout();
                    return;
                } else {
                    /*
                     * Else, set profile and close popup
                     */
                    $scope.profile = $auth.getPayload();
                    $scope.closeSignIn();
                }
            })
                    ["catch"](function(response) {
                /*
                 * If error, display error
                 */
                alert(($filter('translate')('error.login')) + response.data);
            });
            
            email = null;
            password = null;
        };

        $scope.authenticate = function(provider) {

            $auth.authenticate(provider)
                    .then(function() {
                        if ($auth.getPayload().data.groupname !== 'admin') {
                            alert($filter('translate')('Sorry... You are not an administrator'));
                            $scope.logout();
                            return;
                        }
                    })
                    ["catch"](function(response) {
                alert(($filter('translate')('error.login')) + response.data);
            });
        };

        /**
         * Logout action (call by button in laft panel)
         */
        $scope.logout = function() {
            $scope.profile = null;
            $auth.logout();
        };

        /*
         * Display signin popup
         */
        $scope.startSignIn = function() {
            $scope.displaySignIn = true;
        };

        /*
         * Close signin popup
         */
        $scope.closeSignIn = function() {
            $scope.displaySignIn = false;
        };

        /*
         * Display left menu (for small window)
         */
        $scope.displayLeftMenu = function() {
            $scope.showLeftMenu = !$scope.showLeftMenu;
        };

        $scope.start();

    }
    ;
})();