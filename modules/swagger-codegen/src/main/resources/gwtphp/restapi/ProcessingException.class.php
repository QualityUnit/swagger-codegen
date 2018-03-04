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
class RestApi_ProcessingException extends Exception implements RestApi_HasHeaders {

    use RestApi_HeadersTrait;
    
    public function __construct($code, $message, $cause = null) {
        parent::__construct($message, $code, $cause);
    }

    /**
     * @return RestApi_Result
     */
    public function asResult() {
        $result = new RestApi_Result();
        $result->setCode($this->getCode());
        $result->setBody($this->getMessage());
        $result->setHeaders($this->getHeaders());
        return $result;
    }
}