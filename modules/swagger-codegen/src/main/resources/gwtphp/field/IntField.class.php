<?php

class RestApi_TypeUtils_IntField extends RestApi_TypeUtils_Field {

    protected function parse($value) {
        if(is_int($value) || is_float($value)) {
            return (int) $value;
        }
        if (is_bool($value)) {
            return $value ? 1 : 0;
        }
        if (is_numeric($value)) {
            return (int)$value;
        }
        throw new RestApi_TypeUtils_ParseException('int', $value);
    }
}