<?php
namespace Hotmart;
use Hotmart\HotmartStateManager;
use Hotmart\HotmartLogin;
use Hotmart\HotmartToken;

class HotmartFacade
{
    protected $_apiUrl;
    private $_token;
    private $_appId;
    private $_appSecret;
    private $_url;

    public function __construct(string $appId, string $appScrect, string $url)
    {
        $this->_apiUrl = 'http://api.hotmart.com.br';
        $this->_appId = $appId;
        $this->_appSecret = $appScrect;
        $this->_url = $url;
    }

    public function getToken(): string
    {
        $this->setToken();
        return $this->_token;
    }

    private function setToken()
    {
        $session = new HotmartStateManager;
        $code = isset($_REQUEST["code"]) ? $_REQUEST["code"] : '';

        if( empty($code) ) {
            $session->setState();
            $state = $session->getState();
        
            $login = new HotmartLogin($this->_apiUrl, $state, $this->_appId, $this->_appSecret, $this->_url);
            $dialog_url = $login->getDialogUrl();
            header("Location: {$dialog_url}");
        
        } elseif ( $session->verifyStateIsValid() ) {
            
            $params = array();
            $params['client_id'] = $this->_appId;
            $params['redirect_uri'] = $this->_url;
            $params['client_secret'] = $this->_appSecret;
            $params['code'] = $code;
            
            $hotmartToken = new HotmartToken($this->_apiUrl, $params);
            $this->_token = $hotmartToken->getToken();
        }
    }
}