<?php

class RestApi_TypeUtils_ParseException extends Exception {

    private $fieldName;

    public function __construct($type, $value, $fieldName = null) {
        parent::__construct("Unable to convert '". $value ."' to " . $type . '.', 403);
        $this->fieldName = $fieldName;
    }

    public function getFieldName() {
        return $this->fieldName;
    }

    public function setFieldName($fieldName) {
        $this->fieldName = $fieldName;
    }
}