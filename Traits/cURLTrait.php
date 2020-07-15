<?php

namespace nlib\cURL\Traits;

use nlib\cURL\Classes\cURL;

trait cURLTrait {

    private $_curl;

    #region Getter

    public function cURL(string $url) : cURL {
        $instance = (method_exists($this, $method = '_i')) ? $this->{$method}() : 'i';
        if(empty($this->_curl)) $this->setcURL(new cURL($url));
        else $this->_curl->setUrl($url);
        return $this->_curl->setInstance($instance);
    }

    #endregion

    #region Setter
    
    public function setcURL(cURL $curl) : self { $this->_curl = $curl; return $this; }

    #endregion
}