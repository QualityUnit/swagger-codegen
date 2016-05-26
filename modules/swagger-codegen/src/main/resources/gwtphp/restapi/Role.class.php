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
class RestApi_Role {

    private $privileges = array();

    protected function addPrivilege($name) {
        $this->privileges[$name] = true;
    }

    public function hasPrivilege($name) {
        return array_key_exists($name, $this->privileges);
    }
}