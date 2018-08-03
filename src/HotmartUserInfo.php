<?php
namespace Hotmart;
use Hotmart\HotmartRequest;

class HotmartUserInfo 
{
    private $_url;
    private $_request;

    public function __construct(string $url)
    {
        $this->_url = $url . '/user_info';
        $this->_request = new HotmartRequest;
    }

    public function getUserInfo(string $token): array
    {
        $params = array();
        $params['access_token'] = $token;
        $user = $this->_request->send($this->getUrl(), $params);
        
        if (is_array($user))  {
            return $user["UserInfoResponse"];
        } else {
            return $user = array(
                'error' => 'Não foi possível carregar os dados do usuário'
            );
        }
    }

    public function getUrl(): string 
    {
        return $this->_url;
    }
}