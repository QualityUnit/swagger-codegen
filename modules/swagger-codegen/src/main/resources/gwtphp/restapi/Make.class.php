<?php

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
     * @return RestApi_ProcessingException
     */
    public static function error($code = 500, $message = 'Unspecified error.', $cause = null) {
        return self::getInstance()->innerError($code, $message, $cause);
    }

    public static function result($body, $code = 200, $headers = []) {
        return self::getInstance()->innerResult($body, $code, $headers);
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

    /**
     * @return object
     */
    protected function innerOkResult() {
        return RestApi_OkResponse::asResult();
    }

    /**
     * @param int $code
     * @param string $message
     * @param Exception $cause
     * @return RestApi_ProcessingException
     */
    protected function innerError($code, $message, $cause) {
        $exception = new RestApi_ProcessingException($code, $message, $cause);
        $exception->setHeader('Content-Type', 'application/json; charset=utf-8');
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
        $headers['Content-Type'] = 'application/json; charset=utf-8';
        $result->setHeaders($headers);
        return $result;
    }
}