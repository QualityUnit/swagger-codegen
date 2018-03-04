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
class RestApi_TypeUtils_ParseException extends Exception {

    /** @var string */
    private $fieldName;

    /**
     * @param string $type
     * @param string $value
     * @param string $fieldName
     */
    public function __construct($type, $value, $fieldName = '_unset_') {
        parent::__construct("Unable to convert '" . $value . "' to " . $type . '.', 403);
        $this->fieldName = $fieldName;
    }

    /**
     * @return string
     */
    public function getFieldName() {
        return $this->fieldName;
    }

    /**
     * @param string $fieldName
     */
    public function setFieldName($fieldName) {
        $this->fieldName = $fieldName;
    }
}