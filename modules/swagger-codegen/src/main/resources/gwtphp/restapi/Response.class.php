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
class RestApi_Response {
    
    /**
     * @var \Slim\Http\Response
     */
    private $response;
    
    public function __construct(\Slim\Http\Response $response) {
        $this->response = $response;
    }
    
    /**
     * @param mixed $object RestApi_Result or json serializable
     */
    public function setResult($object) {
        if ($object instanceof RestApi_Result) {
            $result = $object;
        } else {
            $headers = [];
            if ($object instanceof RestApi_HasHeaders) {
                $headers = $object->getHeaders();
            }
            $result = RestApi_Make::result(json_encode($object), 200, $headers);
        }
        
        $this->fillResponse($result);
    }
    
    public function setError(Exception $error) {
        $code = 500;
        $message = "Internal server error: {$error->getMessage()}";
        $headers = [];
        if ($error instanceof RestApi_ProcessingException) {
            $code = $error->getCode();
            $headers = $error->getHeaders();
        } else if ($error instanceof RestApi_TypeUtils_ParseException) {
            $code = 400;
            $message = "Invalid field {$error->getFieldName()}: {$error->getMessage()}";
        }
        
        $this->fillResponse(RestApi_Make::errorResult($code, $message, $error, $headers));
    }
    
    public function send() {
        foreach ($this->response->headers() as $name => $value) {
            header("$name: $value", true, $this->response->getStatus());
        }
        echo $this->response->getBody();
    }
    
    private function fillResponse(RestApi_Result $result) {
        foreach ($result->getHeaders() as $name => $value) {
            $this->response->headers()->set($name, $value);
        }
        $this->response->setStatus($result->getCode());
        $this->response->setBody($result->getBody());
    }
}
