<?php

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