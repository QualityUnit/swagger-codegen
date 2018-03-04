<?php

/**
 * Auto generated code. DO NOT EDIT !!!!
 */
trait RestApi_HeadersTrait {

    /**
     * @var string[]
     */
    private $headers = [];

    /**
     * @return string[]
     */
    public function getHeaders() {
        return $this->headers;
    }

    /**
     * @return $this
     */
    public function clearHeaders() {
        $this->headers = [];
        
        return $this;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function removeHeader($name) {
        unset($this->headers[$name]);
        
        return $this;
    }

    /**
     * @param string $name
     * @param string $value
     *
     * @return $this
     */
    public function setHeader($name, $value) {
        $this->headers[$name] = $value;
        
        return $this;
    }
}