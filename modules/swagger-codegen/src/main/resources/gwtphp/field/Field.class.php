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
abstract class RestApi_TypeUtils_Field {

    private $value;
    private $defaultValue;

    /**
     * @param string $type
     * @param mixed $value
     * @param mixed $default
     * @return RestApi_TypeUtils_Field
     * @throws RestApi_TypeUtils_ParseException
     */
    final public static function of($type, $value = null, $default = null) {
        $field = self::resolveType(rtrim($type, '[]'));
        if (strpos($type, '[]')) {
            $field = new ArrayField($field);
        }
        if ($value === null) {
            if ($default !== null) {
                $value = $default;
            }
        }
        $field->set($value);

        return $field;
    }

    final private static function resolveType($type) {
        switch ($type) {
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

    final public function get() {
        return $this->value;
    }

    /**
     * @param $value
     * @throws RestApi_TypeUtils_ParseException
     */
    final public function set($value) {
        if ($value === null) {
            $this->value = $this->getDefaultValue();

            return;
        }
        $this->value = $this->parse($value);
    }

    final protected function getDefaultValue() {
        return $this->defaultValue;
    }

    /**
     * Converts specified value to implementation type.
     *
     * @param mixed $value value to convert
     * @return mixed same value after type conversion
     * @throws RestApi_TypeUtils_ParseException when value cannot be converted
     */
    abstract protected function parse($value);

    final protected function setDefaultValue($value) {
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
        $this->setDefaultValue([]);
    }

    /**
     * @param mixed $value
     * @return array
     * @throws RestApi_TypeUtils_ParseException
     */
    protected function parse($value) {
        if (!is_array($value)) {
            return [];
        }
        $result = [];
        foreach ($value as $key => $item) {
            $result[$key] = $this->type->parse($item);
        }

        return $result;
    }
}