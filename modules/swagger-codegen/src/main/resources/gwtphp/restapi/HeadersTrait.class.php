<?php

trait RestApi_HeadersTrait {
    
    private $headers = [];

    public function getHeaders() {
        return $this->headers;
    }
    
    public function clearHeaders() {
        $this->headers = [];
        
        return $this;
    }
    
    public function removeHeader($name) {
        unset($this->headers[$name]);
        
        return $this;
    }

    public function setHeader($name, $value) {
        $this->headers[$name] = $value;
        
        return $this;
    }
}