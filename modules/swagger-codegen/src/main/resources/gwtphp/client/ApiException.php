<?php
/**
 * @copyright Copyright (c) 2016 Quality Unit s.r.o.
 *   Licensed under the Quality Unit, s.r.o. Standard End User License Agreement,
 *   Version 1.0 (the "License"); you may not use this file except in compliance
 *   with the License. You may obtain a copy of the License at
 *   http://www.qualityunit.com/licenses/license
 */

/**
 * Auto generated code. DO NOT EDIT !!!!
 */
class RestApi_Client_ApiException extends Exception {

    /** @var mixed The HTTP body of the server response either as Json or string. */
    protected $responseBody;

    /** @var string[] The HTTP header of the server response. */
    protected $responseHeaders;

    /** @var mixed The deserialized response object */
    protected $responseObject;

    /**
     * @param string $message Error message
     * @param int $code HTTP status code
     * @param string $responseHeaders HTTP response header
     * @param mixed $responseBody HTTP body of the server response either as Json or string
     */
    public function __construct($message = '', $code = 0, $responseHeaders = null, $responseBody = null) {
        parent::__construct($message, $code);
        $this->responseHeaders = $responseHeaders;
        $this->responseBody = $responseBody;
    }

    /**
     * Gets the HTTP response header
     *
     * @return string HTTP response header
     */
    public function getResponseHeaders() {
        return $this->responseHeaders;
    }

    /**
     * Gets the HTTP body of the server response either as Json or string
     *
     * @return mixed HTTP body of the server response either as Json or string
     */
    public function getResponseBody() {
        return $this->responseBody;
    }

    /**
     * Sets the deseralized response object (during deserialization)
     *
     * @param mixed $obj Deserialized response object
     * @return void
     */
    public function setResponseObject($obj) {
        $this->responseObject = $obj;
    }

    /**
     * Gets the deseralized response object (during deserialization)
     *
     * @return mixed the deserialized response object
     */
    public function getResponseObject() {
        return $this->responseObject;
    }
}
