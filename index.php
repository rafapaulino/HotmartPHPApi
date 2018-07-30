<?php 
session_start();
echo '<pre>';

//https://app.hotmart.com/docs/getting-started.jsp

//autenticacao e token de acesso https://app.hotmart.com/docs/autenticacao.jsp
	
 /********************************************************************************************************
  * 										HOTMART - OAUTH 2 API for PHP
  ********************************************************************************************************/
 /**	
	Este codigo serve como referencia para utilizacao da API hotmart sob OAuth 2
	Referencias OAUTH:
	 - http://en.wikipedia.org/wiki/OAuth
	 - http://tools.ietf.org/html/draft-ietf-oauth-v2-23
	 Testado no PHP 5.3.9 e Apache 2.2.21 VC9
	 
	Informacoes iniciais necessarias
 */
   $hotmart_server = "http://api.hotmart.com.br";
   $app_id = "9971376869094382"; //Codigo que identifica sua aplicacao para o hotmart (fornecida pela hotmart)
   $app_secret = "c6d7115d-7243-4c0e-ad83-cd5f4e3205be"; //Senha gerada para sua aplicacao (fornecida pela hotmart)
   $my_url = "http://localhost:81/hotmart/index.php"; //URL de redirecionamento apos autenticacao no login hotmart e a geracao do code 
   
   /**
   
   Voce trabalhara com tres tipos diferentes de requisicoes:
   
   1 - Requisicao de Autorizacao para receber um codigo de acesso
   2 - Requisicao do token utilizando o codigo de acesso
   3 - Requisicao dos servicos hotmart utilizando o token
   
   */
   
   //funcao para submeter um POST
   function do_post_request($url, $data, $optional_headers = null)
	{
	  $php_errormsg = "";
	  $params = array('http' => array(
				  'method' => 'POST',
				  'content' => $data
				));
	  var_dump($params);

	  if ($optional_headers !== null) {
		$params['http']['header'] = $optional_headers;
	  }
	  $ctx = stream_context_create($params);
	  var_dump($ctx);
	  $fp = @fopen($url, 'rb', false, $ctx);
	  var_dump($fp);
	  if (!$fp) {
		throw new Exception("Problem with $url, $php_errormsg");
	  }
	  $response = @stream_get_contents($fp);
	  var_dump($response);
	  if ($response === false) {
		throw new Exception("Problem reading data from $url, $php_errormsg");
	  }
	  return $response;
	}

   
	//Este codigo pode ser utilizando como um include ou filtro onde eh necessario estar autenticado
	//verifica se o code (codigo de acesso) esta na requisicao (ou sessao caso prefira manter o controle do code)
   $code = isset($_REQUEST["code"])?$_REQUEST["code"]:'';

   if(empty($code)) {
	//caso nao possua o code, chama a autorizacao para obter novo code
	//Essa requisicao deve ser via GET
     $_SESSION['state'] = md5(uniqid(rand(), TRUE)); //CSRF protection (http://pt.wikipedia.org/wiki/Cross-site_request_forgery)
	 //echo($_SESSION['state']);
     $dialog_url = $hotmart_server . "/oauth/authorize?client_id=" 
       . $app_id . "&redirect_uri=" . urlencode($my_url) . "&state="
       . $_SESSION['state'] . "&response_type=code";
	  
     echo("<script> top.location.href='" . $dialog_url . "'</script>");
	 //echo("<a href='" . $dialog_url . "'>URL de Chamada da Autorizacao Hotmart</a>");
   }

   if($_REQUEST['state'] == $_SESSION['state']) {
   //De posse do code, agora obtem o token. Deve-se requisitar o token via POST
   //http://wezfurlong.org/blog/2006/nov/http-post-from-php-without-curl/ (Referencia para requisicoes via post)
     $token_url = $hotmart_server . "/oauth/access_token";
	 //conforme referencia? caso apresente problemas com post onde parece nao ter passado os parametros, utilizar a url com '/' no final: $token_url = "http://api.hotmart.com.br/oauth/access_token/";
	 $post_params =   "client_id=" . $app_id . "&redirect_uri=" . urlencode($my_url)
       . "&client_secret=" . $app_secret . "&code=" . $code;
	   
	  //referencia json: http://php.net/manual/pt_BR/function.json-decode.php
	$response_token = json_decode(do_post_request($token_url,$post_params));
     	 
	 /**
	 Exemplo retorno requisicao de Token
		- {"TokenResponse":{"access_token":"d949f8f614a1793f178c4395c67d508e","expires_in":7200000}}
	 */
	 	echo $token = $response_token->{'TokenResponse'}->{'access_token'};
		
	 //Com o token agora eh possivel consultar os servicos hotmart para obter informacoes sobre o usuario
     
     //...
     	   
   }
   else {
     echo("The state does not match. You may be a victim of CSRF.");
   }


//informacoes do usuario https://app.hotmart.com/docs/resources.jsp
   $hotmart_server = "http://api.hotmart.com.br";
   $resource_user_url = $hotmart_server . "/user_info";				       
   $post_params = "access_token=" . $token;
   $response_userinfo = json_decode(do_post_request($resource_user_url,$post_params));
   echo $response_resources = var_dump($response_userinfo) . " ";


   //consultando as compras do usuario https://app.hotmart.com/docs/resources.jsp

   $hotmart_server = "http://api.hotmart.com.br";
   $resource_compra_url = $hotmart_server . "/purchases";				       
   $post_params = "access_token=" . $token;
   $response_compras = json_decode(do_post_request($resource_compra_url,$post_params));
   var_dump($response_compras);
   var_dump($response_compras->{'Purchases'});	

   //consultando as assinaturas do usuário https://app.hotmart.com/docs/resources.jsp
   $hotmart_server = "http://api.hotmart.com.br";   
   $resource_subsc_url = $hotmart_server . "/subscriber";				       
   $post_params = "access_token=" . $token;
   $response_subsc = json_decode(do_post_request($resource_subsc_url,$post_params));
   var_dump($response_subsc->{'Subscriptions'});


/*
//produtos
   $hotmart_server = "http://api.hotmart.com.br";				       
   $resource_produtos_url = $hotmart_server . "/products";				       
   $post_params = "access_token=" . $token . "&login=rafaclasses@gmail.com&categoria=emagrecimento&apenasMinhasAfiliacoes=true";
   $response_produtos = json_decode(do_post_request($resource_produtos_url,$post_params));				       
	//Exemplo dos dados de um produto
	var_dump($response_produtos->{'ProductCatalog'}->{'Products'});
*/

/*
//vendas
	$startDate = '2018-07-01 00:00:00';
	$endDate = '2018-07-30 00:00:00';
    $hotmart_server = "http://api.hotmart.com.br";   
   $resource_sales_url = $hotmart_server . "/sales";				       
   $post_params = "access_token=" . $token . "&startDate=" . $startDate . "&endDate=" . $endDate;
   $response_sales = json_decode(do_post_request($resource_sales_url,$post_params));
    echo(var_dump($response_sales->{'Sales'}[0]));
    */

//historico de vendas
    	$startDate = '2018-07-01 00:00:00';
	$endDate = '2018-07-30 00:00:00';
    $hotmart_server = "http://api.hotmart.com.br";   
   $resource_historysales_url = $hotmart_server . "/sales/history";				       
   $post_params = "access_token=" . $token . "&startDate=" . $startDate . "&endDate=" . $endDate;
   $response_sales = json_decode(do_post_request($resource_historysales_url,$post_params));
    echo(var_dump($response_sales->{'Sales'}[0]));



    //https://app-vlc.hotmart.com/tools/webhook


    /*
	 autenticacao
	 token
	 dados do usuario

	 //provavelmente deve ter sido mudado pela nova api
	 compras do usuario
	 assinaturas
	 produtos
	 vendas
	 historico de vendas
	
	ou seja vc vai pegar via webhook

    */