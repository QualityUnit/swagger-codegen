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
class RestApi_TypeUtils_StringField extends RestApi_TypeUtils_Field {

    protected function parse($value) {
        if(is_string($value)) {
            return $value;
        }
        if(is_numeric($value)) {
            return (string) $value;
        }
        if(is_bool($value)) {
            return $value ? 'true' : 'false';
        }
        throw new RestApi_TypeUtils_ParseException('string', $value);
    }
}