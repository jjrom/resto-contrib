(function() {
    'use strict';

    angular.module('profile', []);

    angular.module('profile')
            .factory('profile', ['$auth', '$cookies',
                function($auth, $cookies) {
                    var profile = {};
                    
                    profile.checkCookies = function(callback){
                        if ($cookies.take5){
                            /*
                             * TODO : interroger serveur pour savoir si token toujours valide
                             */
                            var token = jwt_decode($cookies.take5);
                            callback(token.data);
                        }
                    };

                    profile.login = function(email, password, callback, error_callback) {
                        $auth.login({email: email, password: password})
                                .then(function(results) {
                                    /*
                                     * Decode token
                                     */
                                    $cookies.take5 = results.data.token;
                                    var token = jwt_decode(results.data.token);
                                    callback(token.data);
                                })
                                ["catch"](function(response) {
                            error_callback(response.data);
                        });
                    };

                    profile.authenticate = function(provider, callback, error_callback) {
                        $auth.authenticate(provider)
                                .then(function(results) {
                                    /*
                                     * Decode token
                                     */
                                    var token = jwt_decode(results.data.token);
                                    callback(token.data);
                                })
                                ["catch"](function(response) {
                            error_callback(response.data);
                        });
                    };
                    
                    profile.logout = function(){
                        delete $cookies.take5;
                    };

                    return profile;
                }]);


    angular.module('profile').controller('ProfileController', ['$scope', 
        function($scope) {
            $scope.password = null;
            $scope.email = null;
        }]);

})();