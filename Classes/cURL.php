<?php

namespace nlib\cURL\Classes;

use nlib\cURL\Interfaces\cURLConstantInterface;
use nlib\cURL\Interfaces\cURLInterface;
use nlib\Tool\Interfaces\ArrayTraitInterface;
use nlib\Log\Interfaces\LogTraitInterface;

use nlib\Log\Traits\LogTrait;
use nlib\Tool\Traits\ArrayTrait;

class cURL implements cURLConstantInterface, cURLInterface, ArrayTraitInterface, LogTraitInterface {

    use ArrayTrait;
    use LogTrait;

    private $_url;
    private $_encoding = self::JSON;
    private $_httpheaders = ['Content-Type: application/x-www-form-urlencoded'];

    public function __construct(string $url) { $this->setUrl($url); }

    #region Public

    public function get(...$params) { return $this->call(self::GET, ...$params); }
    public function post(...$params) { return $this->call(self::POST, ...$params); }

    #endregion

    #region Protected

    protected function call(string $type, ...$params) {

        if(empty($this->getUrl())) die('URL cannot be empty.');
        
        $curl = curl_init();
        curl_setopt_array($curl, $this->getOptions($type, ...$params));
        
        $response = curl_exec($curl);
        $error = curl_error($curl);

        curl_close($curl);

        if(!empty($error)) die('cURL error #: ' . $error);

        return $response;
    }

    protected function getOptions(string $type, ...$params) : array {

        if(!in_array(strtoupper($type), self::METHODS)) die ('Type is not correct.');
        
        $options = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $type,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => $this->getHttpheaders(),
        ];

        if(method_exists($this, $method = 'set' . $type . 'Options')) $this->{$method}($options, ...$params);
        
        $this->log(['cURL Options' => json_encode($options)]);

        return $options;
    }

    protected function setPostOptions(array &$options, $params) : self {

        $options[CURLOPT_URL] = $this->getUrl();

        if(method_exists($this, $method = 'get' . $this->getEncoding() . 'Encoding')) :
            $options[CURLOPT_POST] = true;
            $options[CURLOPT_POSTFIELDS] = $this->{$method}($params);
        endif;
        
        return $this;
    }

    protected function setGetOptions(array &$options, $params) : self {
        
        $options[CURLOPT_URL] = $this->getUrl() . '?' . $this->assoc_to_GET($params, 1);
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
    
    #endregion

    #region Setter

    public function setUrl(string $url) : self { $this->_url = $url; return $this; }
    public function setEncoding(string $encoding) : self {
        $this->_encoding = in_array($encoding, self::ENCODINGS)
            ? $encoding : self::JSON; return $this;
    }
    public function setHttpheaders(array $httpheaders) : self { $this->_httpheaders = $httpheaders; return $this; }
    
    #endregion

}