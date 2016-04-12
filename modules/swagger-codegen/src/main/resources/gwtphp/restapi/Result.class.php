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

class RestApi_Result {

    private $code;
    private $body;
    private $headers = array();

    public function getCode() {
        return $this->code;
    }

    public function setCode($code) {
        $this->code = $code;
    }

    public function getBody() {
        return $this->body;
    }

    public function setBody($body) {
        $this->body = $body;
    }

    public function getHeaders() {
        return $this->headers;
    }

    public function setHeaders($headers) {
        $this->headers = $headers;
    }
}