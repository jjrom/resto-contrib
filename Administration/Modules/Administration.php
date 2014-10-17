<?php

/*
 * RESTo
 * 
 * RESTo - REstful Semantic search Tool for geOspatial 
 * 
 * Copyright 2014 Jérôme Gasperi <https://github.com/jjrom>
 * 
 * jerome[dot]gasperi[at]gmail[dot]com
 * 
 * This software is governed by the CeCILL-B license under French law and
 * abiding by the rules of distribution of free software.  You can  use,
 * modify and/ or redistribute the software under the terms of the CeCILL-B
 * license as circulated by CEA, CNRS and INRIA at the following URL
 * "http://www.cecill.info".
 *
 * As a counterpart to the access to the source code and  rights to copy,
 * modify and redistribute granted by the license, users are provided only
 * with a limited warranty  and the software's author,  the holder of the
 * economic rights,  and the successive licensors  have only  limited
 * liability.
 *
 * In this respect, the user's attention is drawn to the risks associated
 * with loading,  using,  modifying and/or developing or reproducing the
 * software by the user in light of its specific status of free software,
 * that may mean  that it is complicated to manipulate,  and  that  also
 * therefore means  that it is reserved for developers  and  experienced
 * professionals having in-depth computer knowledge. Users are therefore
 * encouraged to load and test the software's suitability as regards their
 * requirements in conditions enabling the security of their systems and/or
 * data to be ensured and,  more generally, to use and operate it in the
 * same conditions as regards security.
 *
 * The fact that you are presently reading this means that you have had
 * knowledge of the CeCILL-B license and that you accept its terms.
 * 
 */

/**
 * RESTo v2 Administration module
 * 
 * Authors :
 * 
 *      jerome[dot]gasperi[at]gmail[dot]com
 *      jerome[dot]mourembles[at]capgemini[dot]com
 * 
 * This module provides html hmi to administrate RESTo
 * 
 * ** Administration **
 * 
 * 
 *    |          Resource                                      |     Description
 *    |________________________________________________________|______________________________________
 *    |  GET     administration                                |  MMI administration start
 *    |  GET     administration/users                          |  MMI list users
 *    |  GET     administration/history                        |  MMI list history
 *    |  GET     administration/users/{userid}                 |  MMI informations for {userid}
 *    |  GET     administration/users/{userid}/history         |  MMI history for {userid}
 *    |  POST    administration/users                          |  Add new user
 *    |  POST    administration/users/{userid}/rights          |  Add new rights for {userid}
 *    |  POST    administration/users/{userid}/activate        |  Activate {userid}
 *    |  POST    administration/users/{userid}/deactivate      |  Deactivate {userid}
 * 
 */
class Administration extends RestoModule {

    /*
     * Resto context
     */
    public $context;
    
    /*
     * path to php file (wanted MMI)
     */
    private $file;
    
    /*
     * Current user (only set for administration on a single user)
     */
    public $user = null;
    
    /*
     * Templates root path
     */
    private $templatesRoot;
    
    /*
     * segments
     */
    public $segments;

    /**
     * Constructor
     * 
     * @param RestoContext $context
     * @param array $options : array of module parameters
     */
    public function __construct($context, $options = array()) {
        
        parent::__construct($context, $options);
        
        $this->templatesRoot = isset($options['templatesRoot']) ? $options['templatesRoot'] : '/Modules/Administration/templates';
        
        $this->context = $context;
        $this->startFile = $this->templatesRoot . '/AdministrationTemplateStart.php';
        $this->usersFile = $this->templatesRoot . '/AdministrationTemplateUsers.php';
        $this->userFile = $this->templatesRoot . '/AdministrationTemplateUser.php';
        $this->groupsFile = $this->templatesRoot . '/AdministrationTemplateGroups.php';
        $this->historyFile = $this->templatesRoot . '/AdministrationTemplateHistory.php';
        $this->userHistoryFile = $this->templatesRoot . '/AdministrationTemplateUserHistory.php';
        $this->userCreationFile = $this->templatesRoot . '/AdministrationTemplateUserCreation.php';
        $this->userRightCreation = $this->templatesRoot . '/AdministrationTemplateUserCreationRight.php';
        $this->footer = 'footer.php';
        $this->header = 'header.php';
    }

    /**
     * Run 
     * 
     * @param array $segments
     * @throws Exception
     */
    public function run($segments) {
        
        if ($this->context->user->profile['groupname'] !== 'admin'){
            throw new Exception(($this->context->debug ? __METHOD__ . ' - ' : '') . 'Only available for administrator', 500);
        } 
        if ($this->context->method === 'GET' && $this->context->outputFormat !== 'html') {
            throw new Exception(($this->context->debug ? __METHOD__ . ' - ' : '') . 'Not Found', 404);
        }
        if ($this->context->method === 'POST' && $this->context->outputFormat !== 'json') {
            throw new Exception(($this->context->debug ? __METHOD__ . ' - ' : '') . 'Not Found', 404);
        }
        
        $this->segments = $segments;
        $method = $this->context->method;

        /*
         * Switch on HTTP methods
         */
        switch ($method) {
            case 'GET':
                return $this->processGET();
            case 'POST':
                return $this->processPOST();
            default:
                throw new Exception(($this->context->debug ? __METHOD__ . ' - ' : '') . 'Not Found', 404);
        }
        
    }

    /**
     * Process on HTTP method POST on /administration
     * 
     * @throws Exception
     */
    private function processPOST() {

        /*
         * Can't post file on /administration
         */
        if (!isset($this->segments[0])) {
            throw new Exception(($this->context->debug ? __METHOD__ . ' - ' : '') . 'Not Found', 404);
        }
        /*
         * Switch on url segments
         */
        else {
            switch ($this->segments[0]) {
                case 'users':
                    return $this->processPostUsers();
                case 'groups':
                    return $this->processPostGroups();
                default:
                    throw new Exception(($this->context->debug ? __METHOD__ . ' - ' : '') . 'Not Found', 404);
            }
        }
    }

    /**
     * Process on HTTP method GET on /administration
     * 
     * @throws Exception
     */
    private function processGET() {

        /*
         * Display start page on /administration
         */
        if (!isset($this->segments[0])) {
            $this->file = $this->startFile;
            return $this->toHTML();
        }
        /*
         * Switch on url segments
         */
        else {
            switch ($this->segments[0]) {
                case 'users':
                    return $this->processGetUsers();
                case 'groups':
                    return $this->processGetGroups();
                default:
                    throw new Exception(($this->context->debug ? __METHOD__ . ' - ' : '') . 'Not Found', 404);
            }
        }
    }
    
    /**
     * Process when GET on /administration/groups
     * 
     * @throws Exception
     */
    private function processGetGroups() {

        /*
         * Get user creation MMI
         */   
        if (isset($this->segments[1])) {
            throw new Exception(($this->context->debug ? __METHOD__ . ' - ' : '') . 'Not Found', 404);
        }
        /*
         * Users list MMI
         */
        else {
            $this->file = $this->groupsFile;
            return $this->toHTML();
        }
    }
    
    /**
     * Process when POST on /administration/groups
     * 
     * @throws Exception
     */
    private function processPostGroups() {
   
        if (isset($this->segments[1])) {
            throw new Exception(($this->context->debug ? __METHOD__ . ' - ' : '') . 'Not Found', 404);
        }
        /*
         * Update rights
         */
        else {
            return $this->updateRights();
        }
    }


    /**
     * Process when GET on /administration/users
     * 
     * @throws Exception
     */
    private function processGetUsers() {

        /*
         * Get user creation MMI
         */   
        if (isset($this->segments[1])) {
            if ($this->segments[1] == 'creation') {
                $this->file = $this->userCreationFile;
                return $this->toHTML();
            }
            /*
             * Get user history MMI
             */
            else if ($this->segments[1] == 'history') {
                $this->file = $this->historyFile;
                return $this->toHTML();
            }
            else {
                return $this->processGetUser();
            }
        }
        /*
         * Users list MMI
         */
        else {
            $this->file = $this->usersFile;
            return $this->toHTML();
        }
    }

    /**
     * Process get on /administration/users/{userid}
     * 
     * @throws Exception
     */
    private function processGetUser() {
        
        $this->user = new RestoUser($this->segments[1], null, $this->context->dbDriver, false);
        if ($this->user->profile['userid'] === -1) {
            throw new Exception(($this->context->debug ? __METHOD__ . ' - ' : '') . 'Not Found', 404);
        }
        
        $this->licenses = $this->context->dbDriver->getSignedLicenses($this->user->profile['email']);
        $this->rightsList = $this->context->dbDriver->getRightsList($this->user->profile['email']);
        
        /*
         * Get user informations MMI
         */
        if (!isset($this->segments[2])) {
            $this->file = $this->userFile;
            return $this->toHTML();
        }
        /*
         * Get user history MMI
         */
        else if ($this->segments[2] == 'history') {
            $this->file = $this->userHistoryFile;
            return $this->toHTML();
        }
        /*
         * Get user rights creation
         */
        else if ($this->segments[2] == 'rights') {
            $this->file = $this->userRightCreation;
            return $this->toHTML();
        }
        else {
            throw new Exception(($this->context->debug ? __METHOD__ . ' - ' : '') . 'Not Found', 404);
        }
    }

    /**
     * Process when POST on /administration/users
     * 
     * @throws Exception
     */
    private function processPostUsers() {

        if (isset($this->segments[1])) {
            return $this->processPostUser();
        } else {
            /*
             * Insert user
             */
            return $this->insertUser();
        }
    }

    /**
     * Process when post on /administration/users/{userid}
     * 
     * @throws Exception
     */
    private function processPostUser() {

        if (isset($this->segments[2])) {
            
            /*
             * Activate user
             */
            if ($this->segments[2] == 'activate') {
                return $this->activate();
            }
            /*
             * Deactivate user
             */
            else if ($this->segments[2] == 'deactivate') {
                return $this->deactivate();
            }
            /*
             * Add rights to user
             */
            else if ($this->segments[2] == 'rights') {
                return $this->processPostRights();
            }
            else {
                throw new Exception(($this->context->debug ? __METHOD__ . ' - ' : '') . 'Not Found', 404);
            }
        }
        else {
            /*
             * Update user
             */
            return $this->updateUser();
        }
    }

    /**
     * Process post on /administration/user/{userid}/rights
     * This post is different because it calls a delete method on rights
     * 
     * @throws Exception
     */
    private function processPostRights() {

        if (isset($this->segments[3])) {
            /*
             * This post delete rights passed with data
             */
            if ($this->segments[3] === 'delete') {
                return $this->deleteRights();
            } else if ($this->segments[3] === 'update') {
                return $this->updateRights();
            } else {
                throw new Exception(($this->context->debug ? __METHOD__ . ' - ' : '') . 'Not Found', 404);
            }
        } else {
            return $this->addRights();
        }
    }

    private function updateRights() {
        try {
            /*
             * Get posted data
             */
            $postedData = array();
            $postedData['emailorgroup'] = filter_input(INPUT_POST, 'emailorgroup');
            $postedData['collection'] = filter_input(INPUT_POST, 'collection');
            $postedData['field'] = filter_input(INPUT_POST, 'field');
            $postedData['value'] = filter_input(INPUT_POST, 'value');

            $emailorgroup = $postedData['emailorgroup'];
            $collectionName = ($postedData['collection'] === '') ? null : $postedData['collection'];
            
            /*
             * Posted rights
             */
            $rights = array($postedData['field'] => $postedData['value']);

            $right = $this->context->dbDriver->getRights($emailorgroup, $collectionName);
            if (!$right) {
                /*
                 * Store rights
                 */
                $this->context->dbDriver->storeRights($rights, $emailorgroup, $collectionName);

                /*
                 * Success information
                 */
                return json_encode(array('status' => 'success', 'message' => 'success'));
            }else{
                /*
                 * Upsate rights
                 */
                $this->context->dbDriver->updateRights($rights, $emailorgroup, $collectionName);

                /*
                 * Success information
                 */
                return json_encode(array('status' => 'success', 'message' => 'success'));
            }
        } catch (Exception $ex) {
            throw new Exception(($this->context->debug ? __METHOD__ . ' - ' : '') . 'Error while updating rights', 500);
        }
    }

    /**
     * Add rights 
     * 
     * @throws Exception
     */
    private function addRights() {
        
        try {
            /*
             * Get posted data
             */
            $postedData = array();
            $postedData['emailorgroup'] = filter_input(INPUT_POST, 'emailorgroup');
            $postedData['collection'] = filter_input(INPUT_POST, 'collection');
            $postedData['featureid'] = filter_input(INPUT_POST, 'featureid');
            $postedData['search'] = filter_input(INPUT_POST, 'search');
            $postedData['visualize'] = filter_input(INPUT_POST, 'visualize');
            $postedData['download'] = filter_input(INPUT_POST, 'download');
            $postedData['canput'] = filter_input(INPUT_POST, 'canput');
            $postedData['canpost'] = filter_input(INPUT_POST, 'canpost');
            $postedData['candelete'] = filter_input(INPUT_POST, 'candelete');
            $postedData['filters'] = filter_input(INPUT_POST, 'filters');

            $emailorgroup = $postedData['emailorgroup'];
            $collectionName = ($postedData['collection'] === '') ? null : $postedData['collection'];
            $featureIdentifier = ($postedData['featureid'] === '') ? null : $postedData['featureid'];

            /*
             * Posted rights
             */
            $rights = array('search' => $postedData['search'], 'visualize' => $postedData['visualize'], 'download' => $postedData['download'], 'canput' => $postedData['canput'], 'canpost' => $postedData['canpost'], 'candelete' => $postedData['candelete'], 'filters' => $postedData['filters']);

            /*
             * Store rights
             */
            $this->context->dbDriver->storeRights($rights, $emailorgroup, $collectionName, $featureIdentifier);

            /*
             * Success information
             */
            return json_encode(array('status' => 'success', 'message' => 'success'));
            
        } catch (Exception $e) {
            throw new Exception(($this->context->debug ? __METHOD__ . ' - ' : '') . 'Error while creating rights', 500);
        }
    }

    /**
     * Delete rights
     * 
     * @throws Exception
     */
    private function deleteRights() {
        try {
            $rights = array();
            $rights['emailorgroup'] = filter_input(INPUT_POST, 'emailorgroup');
            $rights['collection'] = filter_input(INPUT_POST, 'collection');
            $rights['featureid'] = filter_input(INPUT_POST, 'featureid');
            
            if ($rights) {
                $this->context->dbDriver->deleteRights($rights['emailorgroup'], ($rights['collection'] === '' ? null : $rights['collection']), ($rights['featureid'] === '' ? null : $rights['featureid']));
                return json_encode(array('status' => 'success', 'message' => 'success'));
            }
            else {
                throw new Exception();
            }
        } catch (Exception $ex) {
            throw new Exception(($this->context->debug ? __METHOD__ . ' - ' : '') . 'Error while deleting rights', 500);
        }
    }

    /**
     * insertUser - insert new user in database
     * 
     * @throws Exception
     */
    private function insertUser() {
        $userParam = array_merge($_POST);
        if ($userParam) {
            try {
                $this->context->dbDriver->storeUserProfile($userParam);
                return json_encode(array('status' => 'success', 'message' => 'success'));
            } catch (Exception $e) {
                throw new Exception(($this->context->debug ? __METHOD__ . ' - ' : '') . 'User not created', 500);
            }
        } else {
            throw new Exception(($this->context->debug ? __METHOD__ . ' - ' : '') . 'No data to create user', 500);
        }
    }
    
    /**
     * updateUser - update new user in database
     * 
     * @throws Exception
     */
    private function updateUser() {
        $userParam = array_merge($_POST);
        if ($userParam) {
            try {
                $this->context->dbDriver->updateUserProfile($userParam);
                return json_encode(array('status' => 'success', 'message' => 'success'));
            } catch (Exception $e) {
                throw new Exception(($this->context->debug ? __METHOD__ . ' - ' : '') . 'User not updated', 500);
            }
        } else {
            throw new Exception(($this->context->debug ? __METHOD__ . ' - ' : '') . 'No data to update user', 500);
        }
    }


    /**
     * Activate user
     * 
     * @throws Exception
     */
    private function activate() {
        try {
            $this->context->dbDriver->activateUser($this->segments[1]);
            return json_encode(array('status' => 'success', 'message' => 'success'));
        } catch (Exception $e) {
            throw new Exception(($this->context->debug ? __METHOD__ . ' - ' : '') . 'Error while activating user', 500);
        }
    }

    /**
     * Deactivate user
     * 
     * @throws Exception
     */
    private function deactivate() {
        try {
            $this->context->dbDriver->deactivateUser($this->segments[1]);
            return json_encode(array('status' => 'success', 'message' => 'success'));
        } catch (Exception $ex) {
            throw new Exception(($this->context->debug ? __METHOD__ . ' - ' : '') . 'Error while deactivating user', 500);
        }
    }

    /**
     * toHTML
     */
    public function toHTML() {
        return RestoUtil::get_include_contents(realpath(dirname(__FILE__)) . '/../../../themes/' . $this->context->config['theme'] . $this->file, $this);
    }
}
