<?php

namespace nlib\cURL\Interfaces;

use nlib\cURL\Classes\cURL;

interface cURLTraitInterface {

    /**
     *
     * @param string $url
     * @return cURL
     */
    public function cURl(string $url) : cURL;
    
    /**
     *
     * @param cURL $curl
     * @return self
     */
    public function setcURL(cURL $curl);
}