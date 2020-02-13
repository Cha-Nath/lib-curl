<?php

namespace nlib\cURL\Traits;

use nlib\cURL\Classes\cURL;

trait cURLTrait {

    private $_curl;

    #region Getter

    public function cURL(string $url = '') : cURL {
        if(empty($this->_curl)) $this->setcURL(new cURL($url));
        return $this->_curl;
    }

    #endregion

    #region Setter
    
    public function setcURL(cURL $curl) : self { $this->_curl = $curl; return $this; }

    #endregion
}