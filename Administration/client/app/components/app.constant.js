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
            .constant('CONFIG', {
                /*
                 * RESTo server is accessible from this URL
                 */
                'restoServerUrl': 'http://localhost/resto',
                /*
                 * Administration end point - accessible from :
                 *      restoServerUrl + adminstrationEndPoint
                 */
                'administrationEndpoint': '/administration',
                /*
                 * Auth methods
                 */
                'auth': {
                    /*
                     * Set displayLocalAuth true if you want to use local
                     * authentification
                     */
                    'displayLocalAuth': true,
                    /*
                     * Configuration parameters to use an oauth2 authentication
                     */
                    'oauth2': {
                        'name': 'theia',
                        'signUpUrl': 'https://sso.theia-land.fr/theia/app/register/register.xhtml',
                        'authorizeUrl': 'https://sso.theia-land.fr/oauth2/authorize',
                        'clientId': '<your client id>',
                        "requiredUrlParams": [
                            "scope"
                        ]
                    }
                },
                /*
                 * Number of results loaded per request
                 */
                'offset': 55,
                /*
                 * Defined filters for history items
                 */
                'filters': {
                    'methods': ['POST', 'GET', 'PUT', 'DELETE'],
                    'services': ['download', 'create', 'insert', 'search', 'visualize']
                }
            });

})();