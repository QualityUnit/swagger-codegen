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