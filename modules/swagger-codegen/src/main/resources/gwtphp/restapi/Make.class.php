<?php

class RestApi_Make {

    /** @var RestApi_Make */
    private static $instance;

    public static function init(RestApi_Make $instance) {
        self::$instance = $instance;
    }

    /**
     * @return object
     */
    public static function okResponse() {
        return self::getInstance()->innerOkResponse();
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
    protected function innerOkResponse() {
        return RestApi_OkResponse::instance();
    }

    /**
     * @param int $code
     * @param string $message
     * @param Exception $cause
     * @return RestApi_ProcessingException
     */
    protected function innerError($code, $message, $cause) {
        return new RestApi_ProcessingException($code, $message, $cause);
    }
}