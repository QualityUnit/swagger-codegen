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

    /** @var string[] */
    private $privileges = [];

    /**
     * @param string $privilegeName
     */
    protected function addPrivilege($privilegeName) {
        $this->privileges[$privilegeName] = true;
    }

    /**
     * @param string $privilegeName
     *
     * @return bool
     */
    public function hasPrivilege($privilegeName) {
        return array_key_exists($privilegeName, $this->privileges);
    }

    /**
     * @return string[]
     */
    public function getPrivileges() {
    	return array_keys($this->privileges);
    }
}
