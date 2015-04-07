'use strict';

angular.module('administration').config(['$routeProvider',
    function($routeProvider) {

        $routeProvider
                .when('/home', {
                    templateUrl: "components/home/home.html",
                    controller: "HomeController"
                })
                .when('/users', {
                    templateUrl: "components/users/users.html",
                    controller: "UsersController"
                })
                .when('/collections', {
                    templateUrl: "components/collections/collections.html",
                    controller: "CollectionsController"
                })
                .when('/history', {
                    templateUrl: "components/history/history.html",
                    controller: "HistoryController"
                })
                .when('/stats', {
                    templateUrl: "components/stats/stats.html",
                    controller: "StatsController"
                })
                .when('/users/:userid', {
                    templateUrl: 'components/user/user.html',
                    controller: 'UserController'
                })
                .when('/users/:userid/:section', {
                    templateUrl: 'components/user/user.html',
                    controller: 'UserController'
                })
                .otherwise({
                    redirectTo: '/home'
                });

    }]);