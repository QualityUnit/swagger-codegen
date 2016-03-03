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

class RestApi_Params {
    
    const OWN_SCOPE_SUFFIX = '.own';
    
    /**
     * @var \Slim\Http\Request
     */
    private $request;
    private $defaultValues = array();
    private $scopes;
    
    public function __construct(\Slim\Http\Request $request, array $scopes = array()) {
        $this->request = $request;
        $this->scopes = $scopes;
    }

    public function check($name, $required, $defaultValue, $type, array $allowedValues = null) {
        $this->defaultValues[$name] = $defaultValue;
        $value = $this->get($name);
        if ($value == '' && $required) {
            throw RestApi_Make::error(400, sprintf('Param %s is required', $name));
        }
        if ($allowedValues !== null && !in_array($value, $allowedValues)) {
            throw RestApi_Make::error(400, sprintf('Only following values are allowed for %s: %s', $name, implode(',', $allowedValues)));
        }
        try {
            RestApi_TypeUtils_Field::of($type, $this->get($name));
        } catch (RestApi_TypeUtils_ParseException $e) {
            $e->setFieldName($name);
            throw $e;
        }
    }
    
    public function get($name) {
        $value = $this->request->get($name);
        if ($value === null) {
            $value = $this->request->post($name);
        } 
        if ($value === null) {
            $value = @$this->defaultValues[$name];
        }
        return $value;
    }
    
    public function getBody() {
        $body = $this->request->getBody();
        if ($body == '') {
            return array();
        }
        return json_decode($body, true);
    }
    
    public function getScopes($includeOwn = true) {
        if ($includeOwn) {
            return $this->scopes;
        }
        $scopes = array();
        foreach ($this->scopes as $scope) {
            if (substr($scope, -strlen(self::OWN_SCOPE_SUFFIX)) === self::OWN_SCOPE_SUFFIX) {
                continue;
            }
            $scopes[] = $scope;
        }
        return $scopes;
    }
}
