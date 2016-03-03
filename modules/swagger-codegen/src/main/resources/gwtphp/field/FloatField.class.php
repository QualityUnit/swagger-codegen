<?php

class RestApi_TypeUtils_FloatField extends RestApi_TypeUtils_Field {

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