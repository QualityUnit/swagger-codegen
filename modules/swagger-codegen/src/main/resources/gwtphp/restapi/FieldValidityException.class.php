<?php

class RestApi_FieldValidityException extends Exception {

    /** @var string */
    private $fieldName;

    /**
     * @param string $fieldName
     * @param string $message
     */
    public function __construct($fieldName, $message) {
        parent::__construct($message);
        $this->fieldName = $fieldName;
    }

    /**
     * @return string
     */
    public function getFieldName() {
        return $this->fieldName;
    }
}