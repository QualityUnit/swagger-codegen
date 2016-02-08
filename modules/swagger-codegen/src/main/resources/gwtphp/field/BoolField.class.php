<?php

class RestApi_TypeUtils_BoolField extends RestApi_TypeUtils_Field {
    private static $true = array('true', 'y');
    private static $false = array('false', 'n', '');

    protected function parse($value) {
        if(is_bool($value)) {
            return $value;
        }
        if(is_numeric($value)) {
            return $value != 0;
        }
        if(is_string($value)) {
            $value = strtolower($value);
            if(in_array($value, self::$true)) {
                return true;
            }
            if(in_array($value, self::$false)) {
                return false;
            }
        }
        throw new RestApi_TypeUtils_ParseException('bool', $value);
    }
}