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
class RestApi_Client_ApiClient {

    public static $PATCH = 'PATCH';
    public static $POST = 'POST';
    public static $GET = 'GET';
    public static $HEAD = 'HEAD';
    public static $OPTIONS = 'OPTIONS';
    public static $PUT = 'PUT';
    public static $DELETE = 'DELETE';

    private $accessToken;
    private $host;
    private $userAgent = 'PHP-swagger';
    private $curlTimeout = 30;

    /**
     * @param string $host
     * @param string $accessToken
     */
    public function __construct($host, $accessToken = null) {
        $this->host = $host;
        $this->accessToken = $accessToken;
    }


    /**
     * Make the HTTP call (Sync)
     *
     * @param string $resourcePath path to method endpoint
     * @param string $method method to call
     * @param array $queryParams parameters to be place in query URL
     * @param mixed $postData empty string or model object to be included in request body
     * @param array $headerParams parameters to be place in request header
     * @param string $responseType expected response type of the endpoint
     *
     * @throws \InvalidArgumentException
     * @throws RestApi_Client_ApiException on a non 2xx response
     * @return mixed
     */
    public function callApi($resourcePath, $method, $queryParams, $postData, $headerParams,
        $responseType = null) {
        if ($this->getHost() == '') {
            throw new InvalidArgumentException('Api client host not specified.');
        }

        $headers = [];

        if ($this->getAccessToken() != '') {
            $headerParams['apikey'] = $this->getAccessToken();
        }

        foreach ($headerParams as $key => $val) {
            $headers[] = "$key: $val";
        }

        $url = $this->getHost() . $resourcePath;

        // form data
        if ($postData and in_array('Content-Type: application/x-www-form-urlencoded', $headers)) {
            $postData = http_build_query($postData);
        } elseif ((is_object($postData) or is_array($postData))
            and !in_array('Content-Type: multipart/form-data', $headers)) { // json model
            $postData = json_encode($postData->getData());
        }

        $curl = curl_init();
        // set timeout, if needed
        if ($this->getCurlTimeout() !== 0) {
            curl_setopt($curl, CURLOPT_TIMEOUT, $this->getCurlTimeout());
        }
        // return the result on success, rather than just true 
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        // disable SSL verification
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

        if (!empty($queryParams)) {
            $url = ($url . '?' . http_build_query($queryParams));
        }

        if ($method === self::$POST) {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
        } elseif ($method === self::$HEAD) {
            curl_setopt($curl, CURLOPT_NOBODY, true);
        } elseif ($method === self::$OPTIONS) {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'OPTIONS');
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
        } elseif ($method === self::$PATCH) {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PATCH');
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
        } elseif ($method === self::$PUT) {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
        } elseif ($method === self::$DELETE) {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
        } elseif ($method !== self::$GET) {
            throw new RestApi_Client_ApiException('Method ' . $method . ' is not recognized.', 500);
        }
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT, $this->getUserAgent());
        curl_setopt($curl, CURLOPT_VERBOSE, 0);

        // obtain the HTTP response headers
        curl_setopt($curl, CURLOPT_HEADER, 1);

        // Make the request
        $response = curl_exec($curl);
        $http_header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $http_header = $this->http_parse_headers(substr($response, 0, $http_header_size));
        $http_body = substr($response, $http_header_size);
        $response_info = curl_getinfo($curl);

        // Handle the response
        $errNo = curl_errno($curl);
        if ($errNo > 0) {
            $errMessage = "API call to $url failed (errno: $errNo): ";
            if ($errNo === CURLE_OPERATION_TIMEOUTED) {
                $timeout = $this->getCurlTimeout();
                $errMessage = "API call to $url timed out ($timeout s): ";
            }
            throw new RestApi_Client_ApiException($errMessage . json_encode($response_info), 500);
        }

        if ($response_info['http_code'] >= 200 && $response_info['http_code'] <= 299) {
            // return raw body if response is a file
            if ($responseType === 'file' || $responseType === 'string') {
                return [$http_body, $response_info['http_code'], $http_header];
            }

            $data = json_decode($http_body, true);
            if (json_last_error() > 0) {
                $data = $http_body;
            }
        } else {
            $data = json_decode($http_body, true);
            if (json_last_error() > 0) {
                $data = $http_body;
            }

            throw new RestApi_Client_ApiException(
                '[' . $response_info['http_code'] . "] Error connecting to the API ($url)",
                $response_info['http_code'], $http_header, $data
            );
        }

        return [$data, $response_info['http_code'], $http_header];
    }

    /**
     * @return string|null
     */
    public function getAccessToken() {
        return $this->accessToken;
    }

    /**
     * @return int
     */
    public function getCurlTimeout() {
        return $this->curlTimeout;
    }

    /**
     * @return string
     */
    public function getHost() {
        return $this->host;
    }

    /**
     * @return string
     */
    public function getUserAgent() {
        return $this->userAgent;
    }

    /**
     * Return the header 'Accept' based on an array of Accept provided
     *
     * @param string[] $accept Array of header
     *
     * @return string Accept (e.g. application/json)
     */
    public function selectHeaderAccept($accept) {
        if (count($accept) === 0 || (count($accept) === 1 && $accept[0] === '')) {
            return null;
        }

        if (preg_grep("/application\/json/i", $accept)) {
            return 'application/json';
        }

        return implode(',', $accept);
    }

    /**
     * Return the content type based on an array of content-type provided
     *
     * @param string[] $content_type Array fo content-type
     *
     * @return string Content-Type (e.g. application/json)
     */
    public function selectHeaderContentType($content_type) {
        if (count($content_type) === 0 || (count($content_type) === 1 && $content_type[0] === '')) {
            return 'application/json';
        }

        if (preg_grep("/application\/json/i", $content_type)) {
            return 'application/json';
        }

        return implode(',', $content_type);
    }

    /**
     * @param int $curlTimeout
     *
     * @return RestApi_Client_ApiClient
     */
    public function setCurlTimeout($curlTimeout) {
        $this->curlTimeout = $curlTimeout;

        return $this;
    }

    /**
     * @param string $userAgent
     *
     * @return RestApi_Client_ApiClient
     */
    public function setUserAgent($userAgent) {
        $this->userAgent = $userAgent;

        return $this;
    }

    /**
     * Return an array of HTTP response headers
     *
     * @param string $raw_headers A string of raw HTTP response headers
     *
     * @return string[] Array of HTTP response heaers
     */
    protected function http_parse_headers($raw_headers) {
        // ref/credit: http://php.net/manual/en/function.http-parse-headers.php#112986
        $headers = [];
        $key = '';

        foreach (explode("\n", $raw_headers) as $h) {
            $h = explode(':', $h, 2);

            if (isset($h[1])) {
                if (!isset($headers[$h[0]])) {
                    $headers[$h[0]] = trim($h[1]);
                } elseif (is_array($headers[$h[0]])) {
                    $headers[$h[0]] = array_merge($headers[$h[0]], [trim($h[1])]);
                } else {
                    $headers[$h[0]] = array_merge([$headers[$h[0]]], [trim($h[1])]);
                }

                $key = $h[0];
            } else {
                if (substr($h[0], 0, 1) === "\t") {
                    $headers[$key] .= "\r\n\t" . trim($h[0]);
                } elseif (!$key) {
                    $headers[0] = trim($h[0]);
                }
                trim($h[0]);
            }
        }

        return $headers;
    }
}