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
class RestApi_Make {

    /** @var RestApi_Make */
    private static $instance;

    public static function init(RestApi_Make $instance) {
        self::$instance = $instance;
    }

    /**
     * @return RestApi_Result
     */
    public static function okResult() {
        return self::getInstance()->innerOkResult();
    }

    /**
     * @param int $code
     * @param string $message
     * @param Exception $cause
     * @return RestApi_Result
     */
    public static function error($code = 500, $message = 'Unspecified error.', $cause = null) {
        return self::getInstance()->innerError($code, $message, $cause);
    }

    /**
     * @param int $code
     * @param string $message
     * @param Exception $cause
     * @return RestApi_Result
     */
    public static function errorResult($code = 500, $message = 'Unspecified error.', $cause = null, $headers = []) {
        return self::getInstance()->innerErrorResult($code, $message, $cause, $headers);
    }

    public static function result($body, $code = 200, $headers = array()) {
        return self::getInstance()->innerResult($body, $code, $headers);
    }
    
    public static function run(\Slim\Slim $app) {
    	return self::getInstance()->innerRun($app);
    }

    /**
     * @return RestApi_Make
     */
    private static function getInstance() {
        if(self::$instance == null) {
            self::init(new RestApi_Make());
        }
        return self::$instance;
    }
    
    protected function innerRun(\Slim\Slim $app) {
    	$app->run();
    }

    /**
     * @return object
     */
    protected function innerOkResult() {
        return RestApi_Make::result(new stdClass());
    }

    /**
     * @param int $code
     * @param mixed $object json encodable body
     * @param Exception $cause
     * @return RestApi_ProcessingException
     */
    protected function innerErrorResult($code, $object, $cause = null, $headers = []) {
        $result = new RestApi_Result();
        $result->setCode($code);
        $result->setBody(json_encode($object));
        $this->initHeaders($headers);
        $result->setHeaders($headers);
        return $result;
    }

    /**
     * @param int $code
     * @param string $message
     * @param Exception $cause
     * @return RestApi_ProcessingException
     */
    protected function innerError($code, $message, $cause) {
        $exception = new RestApi_ProcessingException($code, $message, $cause);
        $headers = $exception->getHeaders();
        $this->initHeaders($headers);
        foreach ($headers as $header => $value) {
            $exception->setHeader($header, $value);
        }
        return $exception;
    }

    /**
     * @param mixed $body
     * @param int $code
     * @param string[] $headers
     * @return RestApi_Result
     */
    protected function innerResult($body, $code, $headers) {
        $result = new RestApi_Result();
        $result->setCode($code);
        $result->setBody(json_encode($body));
        $this->initHeaders($headers);
        $result->setHeaders($headers);
        return $result;
    }

    protected function initHeaders(array &$headers) {
        $this->initContentHeaders($headers);
        $this->initCacheHeaders($headers);
        $this->initAccessControlHeaders($headers);
    }

    protected function initContentHeaders(array &$headers) {
        if(isset($headers['Content-Type'])) {
            return;
        }
        $headers['Content-Type'] = 'application/json; charset=utf-8';
    }

    protected function initCacheHeaders(array &$headers) {
        if(isset($headers['Cache-Control']) || isset($headers['Pragma']) || isset($headers['Expired'])) {
            return;
        }
        $headers['Cache-Control'] = 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0';
        $headers['Pragma'] = 'no-cache';
        $headers['Expires'] = '26 Jun 1997 05:00:00 GMT';
    }

    protected function initAccessControlHeaders(array &$headers) {
        if(isset($headers['Access-Control-Allow-Origin'])) {
            return;
        }
        $headers['Access-Control-Allow-Origin'] = '*';
    }
}