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
abstract class RestApi_TypeUtils_Field {

    private $value = null;
    private $defaultValue = null;

    /**
     * @param string $type
     * @param mixed $value
     * @param mixed $default
     * @return RestApi_TypeUtils_Field
     */
    public static final function of($type, $value = null, $default = null) {
        $field = self::resolveType(rtrim($type, '[]'));
        if(strpos($type, '[]')) {
            $field = new ArrayField($field);
        }
        if($value == null) {
            if($default != null) {
                $value = $default;
            }
        }
        $field->set($value);
        return $field;
    }

    private static final function resolveType($type) {
        switch($type) {
            case 'int':
            case 'integer':
                return new RestApi_TypeUtils_IntField();
            case 'double':
            case 'float':
                return new RestApi_TypeUtils_FloatField();
            case 'string':
                return new RestApi_TypeUtils_StringField();
            case 'bool':
            case 'boolean':
                return new RestApi_TypeUtils_BoolField();
            case 'mixed':
            default:
                return new BasicField();
        }
    }

    public final function get() {
        return $this->value;
    }

    /**
     * @param $value
     */
    public final function set($value) {
        if($value === null) {
            $this->value = $this->getDefaultValue();
            return;
        }
        $this->value = $this->parse($value);
    }

    /**
     * Converts specified value to implementation type.
     * @param mixed $value value to convert
     * @return mixed same value after type conversion
     * @throws RestApi_TypeUtils_ParseException when value cannot be converted
     */
    protected abstract function parse($value);

    protected final function getDefaultValue() {
        return $this->defaultValue;
    }

    protected final function setDefaultValue($value) {
        $this->defaultValue = $value;
    }
}

class BasicField extends RestApi_TypeUtils_Field {

    protected function parse($value) {
        return $value;
    }
}

class ArrayField extends RestApi_TypeUtils_Field {

    private $type;

    public function __construct(RestApi_TypeUtils_Field $type) {
        $this->type = $type;
        $this->setDefaultValue(array());
    }

    protected function parse($value) {
        if(!is_array($value)) {
            return array();
        }
        $result = array();
        foreach($value as $key => $item) {
            $result[$key] = $this->type->parse($item);
        }
        return $result;
    }
}