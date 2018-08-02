<?php
namespace Hotmart;

class HotmartStateManager
{
    public function __construct()
    {
        $this->sessionStart();
    }

    protected function sessionStart()
    {
        if (session_status() == PHP_SESSION_NONE)
        session_start();
    }

    public function setState()
    {
        $_SESSION['state'] = md5(uniqid(rand(), TRUE));
    }

    public function getState()
    {
        return $_SESSION['state'];
    }

    private function getRequestState(): string
    {
        return ( isset($_REQUEST['state']) ? $_REQUEST['state'] : '');
    }

    public function verifyStateIsValid(): bool
    {
        $state = $this->getRequestState();
        if ($_SESSION['state'] == $state)
        return true;
        else
        return false;
    }    
}