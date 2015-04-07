'use strict';

angular.module('administration', [
    'infinite-scroll',
    'ngRoute',
    'ngCookies',
    'services',
    'satellizer',
    'profile',
    'pascalprecht.translate'
]);

angular.module('administration')
        .config(['$authProvider', 'CONFIG',
            function($authProvider, CONFIG) {
                /*
                 * Authentication configuration
                 */
                // resto "Connect user" service
                $authProvider.loginUrl = CONFIG.restoURL + '/api/users/connect';

                // resto "Add user" service
                $authProvider.signupUrl = CONFIG.restoURL + '/users';

            }]);

angular.module('administration')
        .config(['$translateProvider', config]);

function config($translateProvider) {

    /*
     * Internationalization
     * (See app/i18n/{lang}.json)
     */
    $translateProvider.useStaticFilesLoader({
        prefix: 'app/i18n/',
        suffix: '.json'
    });

    $translateProvider.preferredLanguage('en');

}
;

angular.module('administration')
        .run(['$route', '$rootScope', '$location', function($route, $rootScope, $location) {
                var original = $location.path;
                $location.path = function(path, reload) {
                    if (reload === false) {
                        var lastRoute = $route.current;
                        var un = $rootScope.$on('$locationChangeSuccess', function() {
                            $route.current = lastRoute;
                            un();
                        });
                    }
                    return original.apply($location, [path]);
                };
            }]);

/* Controllers */

angular.module('administration')
        .controller('main', ['$scope', 'profile', 'initialization',
            function($scope, profile, initialization) {

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

                $scope.init = function() {
                    $scope.showUsers = false;
                    $scope.showHistory = false;
                    $scope.showUser = false;
                    $scope.showHome = false;
                    $scope.showCollections = false;
                    $scope.showStats = false;
                    $scope.selectedUser = null;
                    $scope.showLeftMenu = false;
                };

                $scope.login = function(email, password) {
                    profile.login(email, password,
                            function(profile) {
                                $scope.profile = profile;
                                $scope.profile = profile;
                                if (profile.groupname !== 'admin') {
                                    $scope.profile = null;
                                } else {
                                    initialization.isOK();
                                }
                                $scope.closeSignIn();
                            }, function(error) {
                        alert('error - login : ' + error);
                    });
                };

                $scope.checkCookies = function() {
                    profile.checkCookies(function(profile) {
                        $scope.profile = profile;
                        if (profile.groupname !== 'admin') {
                            $scope.profile = null;
                        } else {
                            initialization.isOK();
                        }
                    });
                };

                $scope.logout = function() {
                    profile.logout();
                    $scope.profile = null;
                };

                $scope.startSignIn = function() {
                    $scope.displaySignIn = true;
                };

                $scope.closeSignIn = function() {
                    $scope.displaySignIn = false;
                };

                $scope.displayLeftMenu = function() {
                    $scope.showLeftMenu = !$scope.showLeftMenu;
                };

                $scope.init();
                $scope.checkCookies();

            }]);