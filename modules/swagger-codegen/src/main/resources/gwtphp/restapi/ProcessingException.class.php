<?php
/**
 *   @copyright Copyright (c) 2012 Quality Unit s.r.o.
 *   @author Juraj Simon
 *   @package LiveAgentPro
 *
 *   Licensed under the Quality Unit, s.r.o. Standard End User License Agreement,
 *   Version 1.0 (the "License"); you may not use this file except in compliance
 *   with the License. You may obtain a copy of the License at
 *   http://www.qualityunit.com/licenses/license
 */

class RestApi_ProcessingException extends Exception {

    public function __construct($code, $message, $cause = null) {
        parent::__construct($message, $code, $cause);
    }
}