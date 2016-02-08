<?php

class RestApi_OkResponse {

    private static $instance = null;

    public static function instance() {
        if(self::$instance == null) {
            self::$instance = new stdClass();
        }
        return self::$instance;
    }
}