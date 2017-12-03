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
class RestApi_TypeUtils_FloatField extends RestApi_TypeUtils_Field {

    /**
     * @param mixed $value
     * @return float
     * @throws RestApi_TypeUtils_ParseException
     */
    protected function parse($value) {
        if (is_numeric($value)) {
            return (float)$value;
        }
        if (is_bool($value)) {
            return $value ? 1.0 : 0.0;
        }
        throw new RestApi_TypeUtils_ParseException('float', $value);
    }
}