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
}