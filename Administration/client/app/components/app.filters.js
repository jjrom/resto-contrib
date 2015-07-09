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
            .filter('split', split);

    function split() {
        return function(input, splitChar, splitIndex) {
            /*
             * TODO : add tests
             */
            return input.split(splitChar)[splitIndex];
        };
    }
    ;

    angular.module('administration')
            .filter('isEmpty', function() {
                var inc;
                return function(obj) {
                    for (inc in obj) {
                        if (obj.hasOwnProperty(inc)) {
                            return false;
                        }
                    }
                    return true;
                };
            });
            
    

})();