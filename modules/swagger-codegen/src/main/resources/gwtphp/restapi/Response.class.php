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

class RestApi_Response {
    
    /**
     * @var \Slim\Http\Response
     */
    private $response;
    
    public function __construct(\Slim\Http\Response $response) {
        $this->response = $response;
    }
    
    public function setResult(RestApi_Result $result) {
        foreach ($result->getHeaders() as $name => $value) {
            $this->response->headers()->set($name, $value);
        }
        $this->response->setStatus($result->getCode());
        $this->response->setBody($result->getBody());
    }
    
    public function setError(Exception $e) {
        if($e instanceof RestApi_TypeUtils_ParseException) {
            $e = RestApi_Make::error(400, "Invalid field " . $e->getFieldName() . ": " . $e->getMessage());
        } else if(!($e instanceof RestApi_ProcessingException)) {
            $e = RestApi_Make::error(500, "Internal server error: " . $e->getMessage());
        }
        $this->response->setStatus($e->getCode());
        $this->response->setBody($e->getMessage());
    }
    
    public function send() {
        foreach ($this->response->headers() as $name => $value) {
            header("$name: $value", true, $this->response->getStatus());
        }
        echo $this->response->getBody();
    }
}
