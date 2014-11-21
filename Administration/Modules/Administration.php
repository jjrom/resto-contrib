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
    
    /*
     * Translations array
     *  array(
     *      'en' => array(
     *          'key' => 'translation',
     *          ...
     *      ),
     *      'fr' => array(
     *          ...
     *      )
     *      ...
     *  )
     */
    protected $translations = array(
        'en' => array(
            '_administration' => 'Administration',
            '_a_activate_user' => 'Activate user',
            '_a_activated' => 'Activated',
            '_a_admin' => 'Administrator',
            '_a_areyousure' => 'Are you sure ?',
            '_a_deactivated' => 'Deactivated',
            '_a_can_post' => 'Can Post',
            '_a_can_put' => 'Can Put',
            '_a_can_delete' => 'Can Delete',
            '_a_choose_service' => 'Choose service',
            '_a_choose_collection' => 'Choose collection',
            '_a_choose_service' => 'Choose service',
            '_a_createrights' => 'Create rights',
            '_a_create' => 'Create',
            '_a_collection_and_feature' => 'Collections and Features',
            '_a_delete_user' => 'Delete user',
            '_a_default' => 'Default',
            '_a_deactivate_user' => 'Deactivate user',
            '_a_download' => 'Download',
            '_a_downloads' => 'Downloads',
            '_a_download_nb' => 'Number of Downloads',
            '_a_email' => 'Email',
            '_a_feature_id' => 'Feature id',
            '_a_history' => 'History',
            '_a_insert' => 'Insert',
            '_a_lastname' => 'Last name',
            '_a_last_download' => 'Last downloads',
            '_a_last_history' => 'Last history',
            '_a_givenname' => 'Given name',
            '_a_groupname' => 'Groupname',
            '_a_notsignedyet' => 'Not signed yet',
            '_a_password_confirmation' => 'Confirm password',
            '_a_profile' => 'Profile',
            '_a_remove' => 'Remove',
            '_a_registrationdate' => 'Registration date',
            '_a_save_right' => 'Save right',
            '_a_save_user' => 'Save user',
            '_a_signedon' => 'Signed on',
            '_a_select_group_name' => 'Select group name',
            '_a_search' => 'Search',
            '_a_searchs' => 'Searchs',
            '_a_service' => 'Service',
            '_a_showfullhistory' => 'Show history',
            '_a_start' => 'Welcome to the administration.',
            '_a_set_default_as_group' => 'Set DEFAULT as group',
            '_a_set_admin_as_group' => 'Set this user administrator',
            '_a_update' => 'Update',
            '_a_unregistered' => 'Unregistered',
            '_a_user_creation' => 'Users creation',
            '_a_username' => 'Username',
            '_a_users' => 'Users',
            '_a_collections_management' => 'Collections management',
            '_a_users_management' => 'Users management',
            '_a_userid' => 'Userid',
            '_a_visualize' => 'Visualize',
            '_true' => 'True',
            '_false' => 'False',
            '_for' => 'for',
            '_a_text_createrights' => 'Specify rights on a specific product of collection'
        ),
        'fr' => array(
            '_administration' => 'Administration',
            '_a_activate_user' => 'Activer utilisateur',
            '_a_activated' => 'Activ&eacute;',
            '_a_admin' => 'Administrateur',
            '_a_areyousure' => 'Supprimer ?',
            '_a_deactivated' => 'D&eacute;sactiv&eacute;',
            '_a_can_post' => 'Peut Post',
            '_a_can_put' => 'Peut Put',
            '_a_can_delete' => 'Peut Delete',
            '_a_choose_service' => 'Choisir service',
            '_a_choose_collection' => 'Choisir collection',
            '_a_choose_service' => 'Choisir service',
            '_a_createrights' => 'Cr&eacute;er un nouveau droit',
            '_a_create' => 'Cr&eacute;er',
            '_a_collection_and_feature' => 'Collections et Features',
            '_a_delete_user' => 'Supprimer utilisateur',
            '_a_default' => 'D&eacute;faut',
            '_a_deactivate_user' => 'D&eacute;sactiver utilisateur',
            '_a_download' => 'T&eacute;l&eacute;charger',
            '_a_downloads' => 'T&eacute;l&eacute;chargements',
            '_a_download_nb' => 'Nombre de T&eacute;l&eacute;chargements',
            '_a_email' => 'Email',
            '_a_feature_id' => 'Feature id',
            '_a_history' => 'Historique',
            '_a_insert' => 'Ins&eacute;rer',
            '_a_last_download' => 'Derniers t&eacute;l&eacute;chargements',
            '_a_lastname' => 'Nom',
            '_a_last_history' => 'Historique r&eacute;cent',
            '_a_givenname' => 'Pr&eacute;nom',
            '_a_groupname' => 'Nom du groupe',
            '_a_notsignedyet' => 'Pas encore signée',
            '_a_password_confirmation' => 'Confirmer mot de passe',
            '_a_profile' => 'Profile',
            '_a_remove' => 'Supprimer',
            '_a_registrationdate' => 'Date d\'enregistrement',
            '_a_save_right' => 'Sauvegarder droits',
            '_a_save_user' => 'Sauvegarder utilisateur',
            '_a_signedon' => 'Signée le',
            '_a_select_group_name' => 'S&eacute;l&eacute;ctionner le groupe',
            '_a_search' => 'Rechercher',
            '_a_searchs' => 'Recherches',
            '_a_service' => 'Service',
            '_a_showfullhistory' => 'Afficher l\'historique',
            '_a_start' => 'Bienvenue dans le module d\'administration.',
            '_a_set_default_as_group' => 'Changer pour le groupe DEFAULT',
            '_a_set_admin_as_group' => 'Faire de cet utilisateur un administrateur',
            '_a_update' => 'Mise a jour',
            '_a_user_creation' => 'Cr&eacute;ation d\'utilisateurs',
            '_a_username' => 'Pseudo',
            '_a_unregistered' => 'Non enregistr&eacute;',
            '_a_collections_management' => 'Administration des collections',
            '_a_users_management' => 'Administration des utilisateurs',
            '_a_userid' => 'Userid',
            '_a_users' => 'Utilisateurs',
            '_a_visualize' => 'Visualisation',
            '_true' => 'Oui',
            '_false' => 'Non',
            '_for' => 'pour',
            '_a_text_createrights' => 'Préciser les droits sur un produit particulier de la collection'
        )
    );

    /**
     * Constructor
     * 
     * @param RestoContext $context
     * @param array $options : array of module parameters
     */
    public function __construct($context, $user, $options = array()) {
        
        parent::__construct($context, $options);
        
        $this->templatesRoot = isset($options['templatesRoot']) ? $options['templatesRoot'] : '/Modules/Administration/templates';
        
        // Set user
        $this->user = $user;
        
        // Set context
        $this->context = $context;
        if (isset($this->context)) {
            if (isset($this->translations) && isset($this->translations[$this->context->dictionary->language])) {
                $this->context->dictionary->addTranslations($this->translations[$this->context->dictionary->language]);
            }
        }
        
        /*
         * Templates
         */
        $this->errorFile = $this->templatesRoot . '/500.php';
        $this->startFile = $this->templatesRoot . '/AdministrationTemplateStart.php';
        $this->usersFile = $this->templatesRoot . '/AdministrationTemplateUsers.php';
        $this->userFile = $this->templatesRoot . '/AdministrationTemplateUser.php';
        $this->groupsFile = $this->templatesRoot . '/AdministrationTemplateCollections.php';
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
        
        if ($this->user->profile['groupname'] !== 'admin'){
            /*
             * Only administrators can access to administration
             */
            throw new Exception(($this->context->debug ? __METHOD__ . ' - ' : '') . 'Only available for administrator', 404);
        } 
        
        if ($this->context->method === 'POST' && $this->context->outputFormat !== 'json') {
            /*
             * Only JSON can be posted
             */
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
                case 'collections':
                    return $this->processPostCollections();
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
            $this->stats = array();
            $this->stats['nb_users'] = $this->context->dbDriver->countUsers();
            $this->stats['nb_downloads'] = $this->context->dbDriver->countService('download');
            $this->stats['nb_search'] = $this->context->dbDriver->countService('search');
            return $this->to($this->startFile);
        }
        /*
         * Switch on url segments
         */
        else {
            switch ($this->segments[0]) {
                case 'users':
                    return $this->processGetUsers();
                case 'collections':
                    return $this->processGetCollections();
                case 'stats':
                    return $this->processStatistics();
                default:
                    throw new Exception(($this->context->debug ? __METHOD__ . ' - ' : '') . 'Not Found', 404);
            }
        }
    }
    
    /**
     * Process when GET on /administration/collections
     * 
     * @throws Exception
     */
    private function processGetCollections() {

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
            $this->groups = $this->context->dbDriver->listGroups();
            $this->collections = $this->context->dbDriver->listCollections();
            return $this->to($this->groupsFile, $this->groups);
        }
    }
    
    /**
     * Process when POST on /administration/collections
     * 
     * @throws Exception
     */
    private function processPostCollections() {
   
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
                return $this->to($this->userCreationFile);
            } else if ($this->segments[1] == 'history') {
                /*
                * Get user history MMI
                */
                
                $this->startIndex = 0;
                $this->numberOfResults = 12;
                $this->keyword = null;
                $this->collectionFilter = null;
                $this->service = null;
                $orderBy = null;
                $ascordesc = null;
                if (filter_input(INPUT_GET, 'startIndex')) {
                    $this->startIndex = filter_input(INPUT_GET, 'startIndex');
                }
                if (filter_input(INPUT_GET, 'numberOfResults')) {
                    $this->numberOfResults = filter_input(INPUT_GET, 'numberOfResults');
                }
                if (filter_input(INPUT_GET, 'collection')) {
                    $this->collectionFilter = filter_input(INPUT_GET, 'collection');
                }
                if (filter_input(INPUT_GET, 'service')) {
                    $this->service = filter_input(INPUT_GET, 'service');
                }
                if (filter_input(INPUT_GET, 'orderBy')) {
                    $orderBy = filter_input(INPUT_GET, 'orderBy');
                }
                if (filter_input(INPUT_GET, 'ascordesc')) {
                    $ascordesc = filter_input(INPUT_GET, 'ascordesc');
                }

                $options = array(
                    'orderBy' => $orderBy,
                    'ascOrDesc' => $ascordesc,
                    'collectionName' => $this->collectionFilter,
                    'service' => $this->service,
                    'startIndex' => $this->startIndex,
                    'numberOfResults' => $this->numberOfResults
                );
                $this->historyList = $this->context->dbDriver->getHistory(null, $options);
                $this->collectionsList = $this->context->dbDriver->listCollections();
     
                return $this->to($this->historyFile, $this->historyList);
            } else {
                return $this->processGetUser();
            }
        } else {
            /*
            * Users list MMI
            */
            $this->min = 0;
            $this->number = 50;
            $this->keyword = null;
            if (filter_input(INPUT_GET, 'min')) {
                $this->min = filter_input(INPUT_GET, 'min');
            }
            if (filter_input(INPUT_GET, 'number')) {
                $this->number = filter_input(INPUT_GET, 'number');
            }
            if (filter_input(INPUT_GET, 'keyword')) {
                $this->keyword = filter_input(INPUT_GET, 'keyword');
                $this->global_search_val = filter_input(INPUT_GET, 'keyword');
            } else {
                $this->keyword = null;
                $this->global_search_val = $this->context->dictionary->translate('_menu_globalsearch');
            }
            $this->usersProfiles = $this->context->dbDriver->getUsersProfiles($this->keyword, $this->min, $this->number);
            
            return $this->to($this->usersFile, $this->usersProfiles);
        }
    }

    /**
     * Process get on /administration/users/{userid}
     * 
     * @throws Exception
     */
    private function processGetUser() {

        $this->_user = new RestoUser($this->segments[1], null, $this->context, false);
        if ($this->_user->profile['userid'] === -1) {
            throw new Exception(($this->context->debug ? __METHOD__ . ' - ' : '') . 'Not Found', 404);
        }

        $this->licenses = $this->context->dbDriver->getSignedLicenses($this->_user->profile['email']);
        $this->collectionsList = $this->context->dbDriver->listCollections();
        
        /*
         * Get dedicated rights for current user
         */
        $this->rightsList = $this->context->dbDriver->getFullRights($this->_user->profile['email']);
        
        /*
         * Check rights on each collections for the user
         */
        foreach ($this->collectionsList as $collection){
            $collectionRights = $this->_user->getRights($collection['collection']);
            
            /*
             * All rights by collections has to be set
             */
            if (array_key_exists($collection['collection'], $this->rightsList)){
                /*
                 * If a right is not set for the user, take the right of the
                 * user's group
                 */
                foreach ($collectionRights as $key => $value) {
                    if (!array_key_exists($key, $this->rightsList[$collection['collection']])){
                        $this->rightsList[$collection['collection']][$key] = $value;
                    }
                    if (!isset($this->rightsList[$collection['collection']][$key])){
                        $this->rightsList[$collection['collection']][$key] = $value;
                    }
                }
            }else{
                /*
                 * If none rights are set for this user on this collection, take
                 * rights of the user's group
                 */
                $this->rightsList[$collection['collection']] = $collectionRights;
            }
        }
        
        if (!isset($this->segments[2])) {
            /*
            * Get user informations MMI
            */
            $options = array(
                'numberOfResults' => 4,
                'service' => 'download'
            );
            $this->historyList = $this->context->dbDriver->getHistory($this->_user->profile['userid'], $options);
            

            return $this->to($this->userFile, $this->_user->profile);
        } else if ($this->segments[2] == 'history') {
            /*
             * Get user history MMI
             */
            $this->_user = new RestoUser($this->segments[1], null, $this->context, false);
            $this->userProfile = $this->_user->profile;
            if (!isset($this->userProfile['email'])) {
                throw new Exception(($this->context->debug ? __METHOD__ . ' - ' : '') . 'Wrong way', 404);
            }
            $this->startIndex = 0;
            $this->numberOfResults = 12;
            if (filter_input(INPUT_GET, 'startIndex')) {
                $this->startIndex = filter_input(INPUT_GET, 'startIndex');
            }
            if (filter_input(INPUT_GET, 'numberOfResults')) {
                $this->numberOfResults = filter_input(INPUT_GET, 'numberOfResults');
            }
            
            $this->collectionFilter = null;
            $this->service = null;
            $orderBy = null;
            $ascordesc = null;
            if (filter_input(INPUT_GET, 'collection')) {
                $this->collectionFilter = filter_input(INPUT_GET, 'collection');
            }
            if (filter_input(INPUT_GET, 'service')) {
                $this->service = filter_input(INPUT_GET, 'service');
            }
            if (filter_input(INPUT_GET, 'orderBy')) {
                $orderBy = filter_input(INPUT_GET, 'orderBy');
            }
            if (filter_input(INPUT_GET, 'ascordesc')) {
                $ascordesc = filter_input(INPUT_GET, 'ascordesc');
            }
            if (filter_input(INPUT_GET, 'limit')) {
                $limit = filter_input(INPUT_GET, 'limit');
            }

            $options = array(
                'orderBy' => $orderBy,
                'ascOrDesc' => $ascordesc,
                'collectionName' => $this->collectionFilter,
                'service' => $this->service,
                'startIndex' => $this->startIndex,
                'numberOfResults' => $this->numberOfResults
            );

            $this->historyList = $this->context->dbDriver->getHistory($this->segments[1], $options);
            
            return $this->to($this->userHistoryFile, $this->historyList);
        } else if ($this->segments[2] == 'rights') {
            
            if (filter_input(INPUT_GET, 'collection')) {
                $collection = filter_input(INPUT_GET, 'collection');
            }else{
                throw new Exception(($this->context->debug ? __METHOD__ . ' - ' : '') . 'Not Found', 404);
            }
            
            /*
             * Get user rights creation MMI
             */
            $this->collectionRight = $collection;
            $this->_user = new RestoUser($this->segments[1], null, $this->context, false);
            $this->userProfile = $this->_user->profile;
            
            return $this->to($this->userRightCreation);
        } else {
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
        } catch (Exception $e) {
            throw new Exception(($this->context->debug ? __METHOD__ . ' - ' : '') . $e->getMessage(), 500);
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
            $postedData['filters'] = filter_input(INPUT_POST, 'filters') === 'null' ? null : filter_input(INPUT_POST, 'filters');
            
            if(!$this->context->dbDriver->featureExists($postedData['featureid'])){
                throw new Exception(($this->context->debug ? __METHOD__ . ' - ' : '') . 'Feature does not exists', 500);
            }

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
            throw new Exception(($this->context->debug ? __METHOD__ . ' - ' : '') . $e->getMessage(), 500);
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
        } catch (Exception $e) {
            throw new Exception(($this->context->debug ? __METHOD__ . ' - ' : '') . $e->getMessage(), 500);
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
                throw new Exception(($this->context->debug ? __METHOD__ . ' - ' : '') . $e->getMessage(), 500);
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
                throw new Exception(($this->context->debug ? __METHOD__ . ' - ' : '') . $e->getMessage(), 500);
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
     * Process statistics
     * 
     * @return type
     * @throws Exception
     */
    private function processStatistics(){
        switch ($this->segments[1]) {
            case 'collections':
                return $this->to(null, $this->statisticsService());
            case 'users':
                if (!isset($this->segments[2])){
                    return $this->to(null, $this->statisticsUsers());
                }else if (isset($this->segments[2]) && !isset($this->segments[3])){
                    return $this->to(null, $this->statisticsService($this->segments[2]));
                }else{
                    throw new Exception(($this->context->debug ? __METHOD__ . ' - ' : '') . 'Not Found', 404);
                }
                break;
            default:
                break;
        }
    }
    
    /**
     * Statistics over users
     * 
     * @return type
     */
    private function statisticsUsers(){
        /**
         * nb users
         * nb download
         * nb visualize
         * nb 
         */
        $statistics = array();
        $statistics['users'] = $this->context->dbDriver->countUsers();
        $statistics['download'] = $this->context->dbDriver->countService('download');
        $statistics['search'] = $this->context->dbDriver->countService('search');
        $statistics['visualize'] = $this->context->dbDriver->countService('resource');
        $statistics['insert'] = $this->context->dbDriver->countService('insert');
        $statistics['create'] = $this->context->dbDriver->countService('create');
        $statistics['update'] = $this->context->dbDriver->countService('update');
        $statistics['remove'] = $this->context->dbDriver->countService('remove');
        return $statistics;
    }
    
    /**
     * statisticsService - services stats on collections
     * 
     * @param int $userid
     * @return type
     */
    private function statisticsService($userid = null){
        /*
         * Statistics for each collections
         */
        $statistics = array();
        $collections = $this->context->dbDriver->listCollections();
        foreach ($collections as $collection) {
            $collection_statistics = array();
            $collection_statistics['download'] = $this->context->dbDriver->countService('download', $collection['collection'], $userid);
            $collection_statistics['search'] = $this->context->dbDriver->countService('search', $collection['collection'], $userid);
            $collection_statistics['visualize'] = $this->context->dbDriver->countService('resource', $collection['collection'], $userid);
            $collection_statistics['insert'] = $this->context->dbDriver->countService('insert', $collection['collection'], $userid);
            $collection_statistics['create'] = $this->context->dbDriver->countService('create', $collection['collection'], $userid);
            $collection_statistics['update'] = $this->context->dbDriver->countService('update', $collection['collection'], $userid);
            $collection_statistics['remove'] = $this->context->dbDriver->countService('remove', $collection['collection'], $userid);
            $statistics[$collection['collection']] = $collection_statistics;
        }
        return $statistics;
    }

    /**
     * toHTML
     */
    public function toHTML() {
        return RestoUtil::get_include_contents(realpath(dirname(__FILE__)) . '/../../../themes/' . $this->context->config['theme'] . $this->file, $this);
    }
    
     /**
     * Output collection description as a JSON stream
     * 
     * @param boolean $pretty : true to return pretty print
     */
    public function toJSON($pretty = false){
        return RestoUtil::json_format($this->data, $pretty);
    }
    
    /**
     * to - return method depending on return type
     * 
     * @param String $file
     * @param array $data
     * @return method
     * @throws Exception
     */
    private function to($file, $data = null){
        if ($this->context->method === 'GET' && $this->context->outputFormat === 'json' && isset($data)) {
            $pretty = false;
            if (filter_input(INPUT_GET, '_pretty')) {
                $pretty = filter_input(INPUT_GET, '_pretty');
            }
            $this->data = $data;
            return $this->toJSON($pretty);
        }else if($this->context->method === 'GET' && $this->context->outputFormat === 'html'){
            if (!isset($file)){
                throw new Exception(($this->context->debug ? __METHOD__ . ' - ' : '') . 'Not Found', 404);
            }
            $this->file = $file;
            return $this->toHTML();
        }else{
            throw new Exception(($this->context->debug ? __METHOD__ . ' - ' : '') . 'Not Found', 404);
        }
    }
}
