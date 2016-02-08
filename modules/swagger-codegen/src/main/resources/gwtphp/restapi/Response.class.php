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
    
    public function setResult($result) {
        $this->response->setBody(json_encode($result));
    }
    
    public function setError(Exception $e) {
        if($e instanceof RestApi_TypeUtils_ParseException) {
            $e = new Gpf_RestApi_ProcessingException(400, "Invalid field " . $e->getFieldName() . ": " . $e->getMessage());
        } else if(!($e instanceof Gpf_RestApi_ProcessingException)) {
            $e = new Gpf_RestApi_ProcessingException(500, sprintf("Internal server error: %s", $e->getMessage()));
        }
        $this->response->setStatus($e->getCode());
        $this->response->setBody($e->getMessage());
    }

}
