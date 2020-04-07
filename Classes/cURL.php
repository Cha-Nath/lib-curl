<?php

namespace nlib\cURL\Classes;

use nlib\cURL\Interfaces\cURLConstantInterface;
use nlib\cURL\Interfaces\cURLInterface;
use nlib\Tool\Interfaces\ArrayTraitInterface;
use nlib\Log\Interfaces\LogTraitInterface;

use nlib\Log\Traits\LogTrait;
use nlib\Path\Classes\Path;
use nlib\Tool\Traits\ArrayTrait;

class cURL implements cURLConstantInterface, cURLInterface, ArrayTraitInterface, LogTraitInterface {

    use ArrayTrait;
    use LogTrait;

    private $_url;
    private $_encoding = self::JSON;
    private $_httpheaders = [];
    private $_content_type = '';

    public function __construct(string $url) { $this->setUrl($url); }

    #region Public

    public function get(...$params) { return $this->call(self::GET, ...$params); }
    public function post(...$params) { return $this->call(self::POST, ...$params); }
    public function put(...$params) { return $this->call(self::PUT, ...$params); }

    #endregion

    #region Protected

    protected function call(string $type, ...$params) {

        if(empty($this->getUrl())) $this->dlog(['\nlib\cURL\Classes\cURL::call' => 'URL cannot be empty.']);
        
        $curl = curl_init();
        curl_setopt_array($curl, $this->getOptions($type, ...$params));
        
        $response = curl_exec($curl);
        $error = curl_error($curl);

        curl_close($curl);

        if(!empty($error)) $this->dlog(['\nlib\cURL\Classes\cURL::call' => 'cURL error #: ' . $error]);

        return $response;
    }

    protected function getOptions(string $type, ...$params) : array {

        if(!in_array(strtoupper($type), self::METHODS)) $this->dlog(['\nlib\cURL\Classes\cURL::getOptions' => 'Type is not correct.']);
        
        $httpheaders = $this->getHttpheaders();
        if(!empty($content_type = $this->getContentType())) $httpheaders[] = $content_type;

        $options = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $type,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => $httpheaders,
        ];

        if(!empty($cookie = $this->getCookie())) :

            Path::i()->setCache();

            $cookie = Path::i()->getCache() . $cookie . '.curl.txt';
            $options[CURLOPT_COOKIESESSION] = true;
            $options[CURLOPT_COOKIEJAR] = $cookie;
            $options[CURLOPT_COOKIEFILE] = $cookie;
        endif;

        if(method_exists($this, $method = 'set' . $type . 'Options')) $this->{$method}($options, ...$params);
        
        $this->log(['cURL Options' => json_encode($options)]);

        return $options;
    }

    protected function setPostOptions(array &$options, $params = []) : self {

        $options[CURLOPT_URL] = $this->getUrl();

        if(method_exists($this, $method = 'get' . $this->getEncoding() . 'Encoding')) :
            $options[CURLOPT_POST] = true;
            $options[CURLOPT_POSTFIELDS] = $this->{$method}($params);
        endif;
        
        return $this;
    }

    protected function setPutOptions(array &$options, $params = []) : self {

        $this->setPostOptions($options, $params);  
        return $this;
    }

    protected function setGetOptions(array &$options, $params = []) : self {
        
        $url = $this->getUrl();
        $starter = preg_match ('/[?]/', $url) ? '&' : '?';
        if(!empty($params)) $url .= $starter . $this->getStringEncoding($params);
        $options[CURLOPT_URL] = $url;
        
        return $this;
    }

    protected function getJsonEncoding(array $params) : string {
        return json_encode($params);
    }

    protected function getStringEncoding(array $params) : string {
        return $this->is_assoc($params) ? $this->assoc_to_GET($params, 1) : $this->array_to_GET($params, 1);
    }

    protected function getArrayEncoding(array $params) : array {
        return $params;
    }

    #endregion

    #region Getter
     
    public function getUrl() : string { return $this->_url; }
    public function getEncoding() : string { return $this->_encoding; }
    public function getHttpheaders() : array { return $this->_httpheaders; }
    public function getContentType() : string { return $this->_content_type; }
    public function getCookie() : string { return $this->_hascookie; }
    
    #endregion

    #region Setter

    public function setUrl(string $url) : self { $this->_url = $url; return $this; }
    public function setEncoding(string $encoding) : self {
        $this->_encoding = in_array($encoding, self::ENCODINGS)
            ? $encoding : self::JSON; return $this;
    }
    public function setHttpheaders(array $httpheaders) : self { $this->_httpheaders = $httpheaders; return $this; }
    public function setContentType(string $content_type) : self {
        $this->_content_type = in_array($content_type, self::CONTENT_TYPES)
            ? $content_type : ''; return $this;
    }
    public function setCookie(string $cookie) : self { $this->_hascookie = $cookie; return $this; }
    
    #endregion

}