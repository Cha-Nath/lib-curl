<?php

namespace nlib\cURL\Interfaces;

interface cURLInterface {

    /**
     *
     * @param [type] ...$params
     * @return mixed
     */
    public function get(...$params);

    /**
     *
     * @param [type] ...$params
     * @return mixed
     */
    public function post(...$params);

    /**
     *
     * @param [type] ...$params
     * @return mixed
     */
    public function put(...$params);

    /**
     *
     * @param [type] ...$params
     * @return mixed
     */
    public function patch(...$params);

    /**
     *
     * @param [type] ...$params
     * @return mixed
     */
    public function delete(...$params);

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
     * @return string
     */
    public function getCookie() : string;

    /**
     *
     * @return array
     */
    public function getOptions() : array;

    /**
     *
     * @return array
     */
    public function getDefaultOptions() : array;

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

    /**
     *
     * @param string $cookie
     * @return self
     */
    public function setCookie(string $cookie);

    /**
     *
     * @param array $options
     * @return self
     */
    public function setOptions(array $options);
}