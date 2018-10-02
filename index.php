<?php

include 'vendor/autoload.php';
use Hotmart\HotmartFacade;

$app_id = "XXXXXXXXXX"; //Codigo que identifica sua aplicacao para o hotmart (fornecida pela hotmart)
$app_secret = "XXXXXXXXX"; //Senha gerada para sua aplicacao (fornecida pela hotmart)
$my_url = "http://localhost:81/hotmartPHPApi/index2.php"; //URL de redirecionamento apos autenticacao no login hotmart e a geracao do code 

$facade = new HotmartFacade($app_id, $app_secret, $my_url);
echo $token = $facade->getToken();
echo '<hr>';
$user = $facade->getUserInfo();
echo '<pre>';
var_dump($user);
echo '</pre>';
echo '<hr>';