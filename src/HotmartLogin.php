<?php 
namespace Hotmart;

class HotmartLogin
{
    private $_url;
    private $_state;
    private $_appId;
    private $_appSecret;
    private $_myUrl;
    private $_dialogUrl;
    private $_redirectUrl;

    public function __construct(string $url, string $state, string $appId, string $secret, string $myUrl)
    {
        $this->_url = $url . '/oauth/authorize?client_id=' . $appId;
        $this->_state = $state;
        $this->_appId = $appId;
        $this->_appSecret = $secret;
        $this->_myUrl = urlencode($myUrl);

        $this->setRedirectUrl();
        $this->setDialogUrl();
    }

    protected function setRedirectUrl()
    {
        $this->_redirectUrl = '&redirect_uri=' . $this->getMyUrl() . '&state=' . $this->getState() . '&response_type=code';
    }

    protected function setDialogUrl()
    {
        $this->_dialogUrl = $this->getUrl() . $this->getRedirectUrl();
    }

    /**
     * @return mixed
     */
    public function getRedirectUrl()
    {
        return $this->_redirectUrl;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->_url;
    }

    /**
     * @return mixed
     */
    public function getDialogUrl()
    {
        return $this->_dialogUrl;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->_state;
    }

    /**
     * @return mixed
     */
    public function getAppId()
    {
        return $this->_appId;
    }

    /**
     * @return mixed
     */
    public function getAppSecret()
    {
        return $this->_appSecret;
    }

    /**
     * @return mixed
     */
    public function getMyUrl()
    {
        return $this->_myUrl;
    }
}