<?php

namespace nlib\cURL\Classes;

use nlib\cURL\Interfaces\cURLConstantInterface;
use nlib\cURL\Interfaces\cURLInterface;
use nlib\Instance\Traits\InstanceTrait;
use nlib\Tool\Interfaces\ArrayTraitInterface;
use nlib\Log\Interfaces\LogTraitInterface;

use nlib\Log\Traits\DebugTrait;
use nlib\Log\Traits\LogTrait;
use nlib\Path\Classes\Path;
use nlib\Tool\Traits\ArrayTrait;

class cURL implements cURLConstantInterface, cURLInterface, ArrayTraitInterface, LogTraitInterface {

    use ArrayTrait;
    use LogTrait;
    use DebugTrait;
    use InstanceTrait;

    private $_url;
    private $_encoding = self::JSON;
    private $_httpheaders = [];
    private $_content_type = '';
    private $_cookie = '';
    private $_options = [];

    public function __construct(string $url) { $this->setUrl($url); }

    #region Public Method

    public function get(...$params) { return $this->call(self::GET, ...$params); }
    public function post(...$params) { return $this->call(self::POST, ...$params); }
    public function put(...$params) { return $this->call(self::PUT, ...$params); }
    public function patch(...$params) { return $this->call(self::PATCH, ...$params); }
    public function delete(...$params) { return $this->call(self::DELETE, ...$params); }

    #endregion

    #region Protected Method

    protected function call(string $type, ...$params) {

        if(empty($this->getUrl())) $this->dlog([__CLASS__ . '::' . __FUNCTION__ => 'URL cannot be empty.']);
        
        $curl = curl_init();
        curl_setopt_array($curl, $options = $this->getHandlerOptions($type, ...$params));
        $this->debug($options);

        $response = curl_exec($curl);
        $error = curl_error($curl);

        curl_close($curl);

        if(!empty($error)) $this->dlog([__CLASS__ . '::' . __FUNCTION__ => 'cURL error #: ' . $error]);

        return $response;
    }

    #endregion

    #region Protected Getter

    protected function getHandlerOptions(string $type, ...$params) : array {

        $log = __CLASS__ . '::' . __FUNCTION__;

        if(!in_array(strtoupper($type), self::METHODS)) $this->dlog([$log => 'Type is not correct.']);
        
        $httpheaders = $this->getHttpheaders();
        if(!empty($content_type = $this->getContentType())) $httpheaders[] = $content_type;

        $options = $this->getDefaultOptions() + [CURLOPT_CUSTOMREQUEST => $type, CURLOPT_HTTPHEADER => $httpheaders];

        if(!empty($cookie = $this->getCookie())) :

            Path::i($this->_i())->setCache();

            $cookie = Path::i($this->_i())->getCache() . $cookie . '.curl.txt';
            $options + [CURLOPT_COOKIESESSION => true, CURLOPT_COOKIEJAR => $cookie, CURLOPT_COOKIEFILE => $cookie];

        endif;

        if(method_exists($this, $method = 'set' . $type . 'HandlerOptions')) $this->{$method}($options, ...$params);

        $options = $this->array_merge($options, $this->getOptions());
        
        $this->log([$log => 'cURL Options : ' . json_encode($options)]);

        return $options;
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

    #region Protected Setter

    protected function setPostHandlerOptions(array &$options, $params = []) : self {

        $options[CURLOPT_URL] = $this->getUrl();

        if(method_exists($this, $method = 'get' . $this->getEncoding() . 'Encoding')) :
            $options[CURLOPT_POST] = true;
            $options[CURLOPT_POSTFIELDS] = $this->{$method}($params);
        endif;
        
        return $this;
    }

    protected function setPutHandlerOptions(array &$options, $params = []) : self {

        $this->setPostHandlerOptions($options, $params);  
        return $this;
    }

    protected function setPatchHandlerOptions(array &$options, $params = []) : self {

        $this->setPostHandlerOptions($options, $params);  
        return $this;
    }

    protected function setDeleteHandlerOptions(array &$options, $params = []) : self {

        $this->setPostHandlerOptions($options, $params);  
        return $this;
    }

    protected function setGetHandlerOptions(array &$options, $params = []) : self {
        
        $url = $this->getUrl();
        $starter = preg_match ('/[?]/', $url) ? '&' : '?';
        if(!empty($params)) $url .= $starter . $this->getStringEncoding($params);
        $options[CURLOPT_URL] = $url;
        
        return $this;
    }

    #endregion

    #region Getter
     
    public function getUrl() : string { return $this->_url; }
    public function getEncoding() : string { return $this->_encoding; }
    public function getHttpheaders() : array { return $this->_httpheaders; }
    public function getContentType() : string { return $this->_content_type; }
    public function getCookie() : string { return $this->_cookie; }
    public function getOptions() : array { return $this->_options; }

    public function getDefaultOptions() : array {
        
        return 
        [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_HTTP_VERSION => \CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYPEER => false,
        ];
    }
    
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
    public function setCookie(string $cookie) : self { $this->_cookie = $cookie; return $this; }
    public function setOptions(array $options) : self { $this->_options = $options; return $this; }
    
    #endregion

}