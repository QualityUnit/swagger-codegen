<?php
/**
 *   @copyright Copyright (c) 2016 Quality Unit s.r.o.
 *
 *   Licensed under the Quality Unit, s.r.o. Standard End User License Agreement,
 *   Version 1.0 (the "License"); you may not use this file except in compliance
 *   with the License. You may obtain a copy of the License at
 *   http://www.qualityunit.com/licenses/license
 */

/**
 * Auto generated code. DO NOT EDIT !!!!
 */
class RestApi_Params {
    
    const OWN_SCOPE_SUFFIX = '.own';
    
    /**
     * @var \Slim\Http\Request
     */
    private $request;
    private $defaultValues = [];
    private $scopes;
    
    public function __construct(\Slim\Http\Request $request, array $scopes = []) {
        $this->request = $request;
        $this->scopes = $scopes;
    }

    /**
     * @param string $fieldName
     * @param bool $required
     * @param string $defaultValue
     * @param string $type
     * @param string[] $allowedValues
     *
     * @throws RestApi_FieldValidityException
     * @throws RestApi_ProcessingException
     */
    public function check($fieldName, $required, $defaultValue, $type, array $allowedValues = []) {
        $this->defaultValues[$fieldName] = $defaultValue;
        $value = $this->get($fieldName);
        if($value === '') {
            if($required) {
                throw new RestApi_ProcessingException(400, sprintf('Param %s is required', $fieldName));
            }

            // not required, no need to type check
            return;
        }
        if (count($allowedValues) > 0 && !in_array($value, $allowedValues, true)) {
            throw new RestApi_ProcessingException(400,
                sprintf('Only following values are allowed for %s: %s', $fieldName, implode(',', $allowedValues))
            );
        }
        try {
            RestApi_TypeUtils_Field::of($type, $this->get($fieldName));
        } catch (RestApi_TypeUtils_ParseException $e) {
            throw new RestApi_FieldValidityException($fieldName, $e->getMessage());
        }
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function wasProvided($name) {
        $value = $this->request->get($name);
        if ($value === null) {
            $value = $this->request->post($name);
        }
        return $value !== null;
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function get($name) {
        $value = $this->request->get($name);
        if ($value === null) {
            $value = $this->request->post($name);
        } 
        if ($value === null) {
            $value = @$this->defaultValues[$name];
        }
        return $value ?: '';
    }

    /**
     * @return mixed
     */
    public function getBody() {
        $body = $this->request->getBody();
        if ($body == '') {
            return [];
        }
        return json_decode($body, true);
    }

    /**
     * @param bool $includeOwn
     *
     * @return string[]
     */
    public function getScopes($includeOwn = true) {
        if ($includeOwn) {
            return $this->scopes;
        }
        $scopes = [];
        foreach ($this->scopes as $scope) {
            if (substr($scope, -strlen(self::OWN_SCOPE_SUFFIX)) === self::OWN_SCOPE_SUFFIX) {
                continue;
            }
            $scopes[] = $scope;
        }
        return $scopes;
    }
}
