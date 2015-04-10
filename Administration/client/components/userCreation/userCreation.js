'use strict';

angular.module('administration').controller('UserCreationController', ['$scope', '$location', '_USERS', 'initialization', 'CONFIG',
    function($scope, $location, _USERS, initialization) {

        if (initialization.ok) {

            $scope.profile = [];

            $scope.createUser = function() {
                
                if ($scope.profile.email === undefined || $scope.profile.password === undefined){
                    alert('please set email and password');
                    return;
                }
                
                var options = [];
                options['email'] = $scope.profile.email;
                options['password'] = $scope.profile.password;
                options['username'] = $scope.profile.username;
                options['givename'] = $scope.profile.givename;
                options['lastname'] = $scope.profile.lastname;

                _USERS.add(options, function() {
                    alert('created');
                }, function(e) {
                    alert('error : ' + e.ErrorMessage);
                });
            };



        }
    }]);