<?php
/**
 *   @copyright Copyright (c) 2012 Quality Unit s.r.o.
 *   @package PostAffiliatePro
 *   @author Juraj Simon
 *
 *   Licensed under the Quality Unit, s.r.o. Standard End User License Agreement,
 *   Version 1.0 (the "License"); you may not use this file except in compliance
 *   with the License. You may obtain a copy of the License at
 *   http://www.qualityunit.com/licenses/license
 */

class RestApi_Auth {

    /**
     * @var RestApi_Auth
     */
    private static $instance;
    
    /**
     * @var RestApi_Role
     */
    private $role;

    /**
     * @var string
     */
    private $apiKey;
    
    /**
     * @param \Slim\Http\Request $request
     * @return RestApi_Auth
     */
    public static final function init(\Slim\Http\Request $request) {
        return self::$instance = static::getInstance($request);
    }

    protected static function getInstance($request) {
        return new RestApi_Auth($request);
    }

    /**
     * @return string|null
     */
    public static function getApiKey() {
        if (self::$instance === null) {
            return null;
        }
        return self::$instance->apiKey;
    }
    
    public static function hasPrivilege($name) {
        if (self::$instance === null) {
            return false;
        }
        return self::$instance->hasPrivilegePrivate($name);
    }

    /**
     * @param string|array $privileges If array is given, method succeeds if user has at least one of the specified privileges
     * @throws Gpf_RestApi_ProcessingException
     */
    public static function checkPrivileges($privileges) {
        if (self::$instance === null) {
            throw new Gpf_RestApi_ProcessingException(403, 'You do not have sufficient privileges');
        }
        self::$instance->checkScope($privileges);
    }
    
    private function __construct(\Slim\Http\Request $request) {
        $key = $request->get('apikey');
        if($key == '') {
            $key = $request->post('apikey');
        }
        if ($key == '') {
            return;
        }
        $this->apiKey = $key;
        $this->role = $this->getRoleFromKey($this->apiKey);
    }

    /**
     * @param string $apiKey
     * @return RestApi_Role
     */
    protected function getRoleFromKey($apiKey) {
        return null;
    }

    /**
     * 
     * @param string|array $scopes If array is given, method succeeds if user has at least one of the specified privileges 
     * @throws Gpf_RestApi_ProcessingException
     */
    public function checkScope($scopes) {
        if (!is_array($scopes)) {
            $scopes = array($scopes);
        }
        if(empty($scopes)) {
            return;
        }
        foreach ($scopes as $scope) {
            if ($this->hasPrivilegePrivate($scope)) {
                return;
            }
        }
        throw new Gpf_RestApi_ProcessingException(403, 'You do not have sufficient privileges');
    }
    
    private function hasPrivilegePrivate($name) {
        if ($this->role === null) {
            return false;
        }
        return $this->role->hasPrivilege($name);
    }
    
}
