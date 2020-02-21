<?php

namespace nlib\cURL\Interfaces;

interface cURLInterface {

    /**
     *
     * @param [type] ...$params
     * @return unknown
     */
    public function get(...$params);

    /**
     *
     * @param [type] ...$params
     * @return unknown
     */
    public function post(...$params);

    /**
     *
     * @return string
     */
    public function getUrl() : string;

    /**
     *
     * @return string
     */
    public function getEncoding() : string;

    /**
     *
     * @return array
     */
    public function getHttpheaders() : array;

    /**
     *
     * @return string
     */
    public function getContentType() : string;
    
    /**
     *
     * @param string $url
     * @return self
     */
    public function setUrl(string $url);

    /**
     *
     * @param string $encoding
     * @return self
     */
    public function setEncoding(string $encoding);

    /**
     *
     * @param array $httpheaders
     * @return self
     */
    public function setHttpheaders(array $httpheaders);

    /**
     *
     * @param string $content_type
     * @return self
     */
    public function setContentType(string $content_type);
}