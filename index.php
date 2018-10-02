<?php

include 'vendor/autoload.php';
use Hotmart\HotmartFacade;

$app_id = "9971376869094382"; //Codigo que identifica sua aplicacao para o hotmart (fornecida pela hotmart)
$app_secret = "c6d7115d-7243-4c0e-ad83-cd5f4e3205be"; //Senha gerada para sua aplicacao (fornecida pela hotmart)
$my_url = "http://localhost:81/hotmartPHPApi/index2.php"; //URL de redirecionamento apos autenticacao no login hotmart e a geracao do code 

$facade = new HotmartFacade($app_id, $app_secret, $my_url);
echo $token = $facade->getToken();
echo '<hr>';
$user = $facade->getUserInfo();
echo '<pre>';
var_dump($user);
echo '</pre>';
echo '<hr>';