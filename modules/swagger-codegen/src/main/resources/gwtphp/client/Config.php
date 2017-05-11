<?php

class RestApi_Client_Config {

    /** @var string */
    protected $accessToken = '';

    /** @var int Timeout (second) of the HTTP request, by default set to 0, no timeout */
    protected $curlTimeout = 10;

    /** @var string */
    protected $host = 'localhost';

    /** @var string User agent of the HTTP request */
    protected $userAgent = 'PHP-Swagger';

    /**
     * @return string
     */
    public function getAccessToken() {
        return $this->accessToken;
    }

    /**
     * @param string $accessToken
     */
    public function setAccessToken($accessToken) {
        $this->accessToken = $accessToken;
    }

    /**
     * @return int
     */
    public function getCurlTimeout() {
        return $this->curlTimeout;
    }

    /**
     * @param int $curlTimeout
     */
    public function setCurlTimeout($curlTimeout) {
        $this->curlTimeout = $curlTimeout;
    }

    /**
     * @return string
     */
    public function getHost() {
        return $this->host;
    }

    /**
     * @param string $host
     */
    public function setHost($host) {
        $this->host = $host;
    }

    /**
     * @return string
     */
    public function getUserAgent() {
        return $this->userAgent;
    }

    /**
     * @param string $userAgent
     */
    public function setUserAgent($userAgent) {
        $this->userAgent = $userAgent;
    }
    
}