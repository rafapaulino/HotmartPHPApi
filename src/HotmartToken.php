<?php
namespace Hotmart;
use Hotmart\HotmartRequest;

class HotmartToken 
{
    private $_url;
    private $_params;
    private $_request;
    private $_expires;
    private $_token;

    public function __construct(string $url, array $params)
    {
        $this->_request = new HotmartRequest;
        $this->_url = $url . '/oauth/access_token';
        $this->_params = $params;
        $this->_token = '';
        $this->_expires = time();
    }

    public function getUrl(): string 
    {
        return $this->_url;
    }

    public function getParams(): array
    {
        return $this->_params;
    }

    public function getToken(): string
    {
        $this->setToken();
        return $this->_token;
    }

    public function getExpires(): string
    {
        return $this->_expires;
    }

    private function setToken()
    {
        $token = $this->_request->send($this->getUrl(),$this->getParams());
        if (is_array($token)) {
            $this->_token = $token['TokenResponse']['access_token'];
            $this->_expires = $token['TokenResponse']['expires_in'];
        }
    }
}