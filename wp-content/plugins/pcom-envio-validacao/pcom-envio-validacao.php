<?php
/**
 * Plugin Name: PontoCom Valida&ccedil;&atilde;o e Envio de Formul&aacute;rios
 * Plugin URI: http://agenciadeinternet.com/
 * Description: Funções para Valida&ccedil;&atilde;o e envio de Fomul&aacute;rios por e-mail.
 * Author: PontoCom Ag&ecirc;ncia de Internet
 * Author URI: http://agenciadeinternet.com/
**/

define ('pcom_envio_validacao_DIR', ABSPATH.PLUGINDIR.'/pcom-envio-validacao/');
define ('pcom_envio_validacao_URL', get_bloginfo('siteurl').'/'.PLUGINDIR.'/pcom-envio-validacao');

	//funções para validação de formulários e mensagens de erro

	function validarItem($name, $campo, $msg = ''){
		if ($_POST[$name] == "" || $_POST[$name] == $campo){
			if($msg) add_error_message($msg);	
			else add_error_message('Preencha "'.$campo.'" corretamente!');
			return 1;
		} else return 0;
	}

	function validarEmail($name, $msg){
		if (!preg_match("/^[a-z0-9_\.\-]+@[a-z0-9_\.\-]*[a-z0-9_\-]+\.[a-z]{2,4}$/", $_POST[$name])){
			if($msg) add_error_message($msg);	
			else add_error_message('Preencha o e-mail corretamente!');
			return 1;
		} else return 0;
	}

	function validarArquivo($name, $msg = '' ){
		if (!(strlen($_FILES[$name]['tmp_name']) > 0)){
			if($msg) add_error_message($msg);	
			else add_error_message('Você deve enviar um arquivo!');
			return 1;
		} else return 0;
	}
	
	//adicionando mensagens à sessão
	function add_error_message($msg){
		if (!isset($_SESSION['error-messages'])){
			$_SESSION['error-messages'] = array() ;
		}
		$_SESSION['error-messages'][] = $msg ;
	}
	
	function add_success_message($msg){
		if (!isset($_SESSION['success-message'])){
			$_SESSION['success-message'] = array() ;
		}
		$_SESSION['success-message'][] = $msg ;
	}

	//obtendo mensagens da sessão
	function get_error_messages()	{
		if (isset($_SESSION['error-messages'])){
			$mensagens = $_SESSION['error-messages'];
			unset($_SESSION['error-messages']);
			return $mensagens;
		}
	}

	function get_success_message() {
		if (isset($_SESSION['success-message'])){
			$mensagens = $_SESSION['success-message'];
			unset($_SESSION['success-message']);
			return $mensagens;
		}
	}

	//Utilizar este código para exibir as mensagens no site
	/*<?php
        $mensagens = get_error_messages();
        if (is_array($mensagens)) {?>
          <div class="erro">
            <ul>
            <?php foreach ($mensagens as $item) {
              echo("<li>" . $item . "</li>");
            }?>
            </ul>
          </div>
        <?php } ?>
            
        <?php
        $success_message = get_success_message();
        if (!empty($success_message)) {?>
          <div class="sucesso">
            <ul>
            <?php echo("<li>" . $success_message[0] . "</li>");?>
            </ul>
          </div>
        <?php } ?>*/

        //Utilizar este CSS
        /*
        .erro{border:dashed 1px #F00; padding:8px; margin:10px; background:#FFE4E1; font-size:15px}
		.erro ul li{font-size: 15px; list-style-type: none;}
		.sucesso{border:dashed 1px #315ca8; padding:8px; margin:10px; background:#66FF66;}
		.sucesso ul li{font-size: 15px; list-style-type: none; color:009900 !important;}
		*/

	// ----------------------------------------------------------------------------------------
	// Envia Fale Conosco
	// ----------------------------------------------------------------------------------------	

	add_action("init","envia_fale_conosco");
        	
    function envia_fale_conosco(){
		if (isset($_POST['form_faleconosco'])){
			if(validarFaleConosco()){
				$campos[0] = adicionaCampo('contato_destino', 'Destino');
				$campos[1] = adicionaCampo('contato_setor', 'Setor');
				$campos[2] = adicionaCampo('contato_nome', 'Nome');
				$campos[3] = adicionaCampo('contato_email', 'E-mail');
				$campos[4] = adicionaCampo('contato_telefone', 'Telefone');
				$campos[5] = adicionaCampo('contato_estado', 'Estado');
				$campos[6] = adicionaCampo('contato_cidade', 'Cidade');
				$campos[7] = adicionaCampo('contato_mensagem', 'Mensagem');
				$message = '
					<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
					<html xmlns="http://www.w3.org/1999/xhtml">
					<head>
					<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
					<title>Fale Conosco FAEP</title>
					</head>
					
					<body>
					<table width="600" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#666; border:4px solid #46765b;">
					  <tr>
						<td colspan="2" bgcolor="#46765b" style="padding:10px 10px 14px 10px; text-transform:uppercase; font-weight:bold; color:#FFF">Novo contato recebido pelo site</td>
					  </tr>';
					  $count=1;
					  foreach($campos as $campo):
					  	$background = (($count%2 == 0) ? 'background:#eeeeee':'');
						  $message.='
						  <tr>
							<td style="padding:10px;'.$background.'"><strong>'.$campo['name_campo'].':</strong></td>
							<td width="80%" style="'.$background.'">'.utf8_decode($_POST[$campo['name_input']]).'</td>
						  </tr>';
						$count++;
					  endforeach;
					$message.='</table>
					</body>
					</html>
				';
				
				require (pcom_envio_validacao_DIR.'class.phpmailer.php');
				require (pcom_envio_validacao_DIR.'class.smtp.php');
				$mail = new PHPMailer();
				$mail->IsHTML(true);
				$mail->Encoding = '8bit';
				$mail->IsSMTP(); // Define que a mensagem será SMTP
				$mail->SMTPAuth = true; // Usa autenticação SMTP? (opcional)
				$mail->Host = get_option('_theme_smtp_host'); // Endereço do servidor SMTP
				$mail->Port = get_option('_theme_smtp_port');
				$mail->Username = get_option('_theme_smtp_user'); // Usuário do servidor SMTP
				$mail->Password = get_option('_theme_smtp_password'); // Senha do servidor SMTP
				//$mail->From = 'dionathanclak@agenciadeinternet.com'; 
				$mail->From = $_POST['contato_email'];
				//$mail->FromName = utf8_decode('Contato FAEP');;
				$mail->FromName = utf8_decode($_POST['contato_nome']);
				//$mail->AddAddress('dionathanclak@agenciadeinternet.com', "Contato FAEP");
				$mail->AddAddress(get_option('_theme_email_recebe_fc'), "Contato FAEP");
				$mail->Subject = utf8_decode("Fale Conosco - FAEP");
				$mail->Body = $message;
				
				if($mail->Send()){
					add_success_message("Contato enviado com sucesso!") ;
				}
				else{
					add_error_message("Não foi possível enviar sua mensagem neste momento. Por favor, tente novamente mais tarde.") ;
				}
			}
		}
	}
	
	function validarFaleConosco(){
		$erros = 0;
		$erros += validarItem('contato_destino', 'Selecione o Destino', 'Favor selecionar o DESTINO!');
		$erros += validarItem('contato_setor', 'Selecione o Setor', 'Favor selecionar o SETOR!');
		$erros += validarItem('contato_nome', 'Digite seu Nome', 'Preencha o NOME corretamente!');
		$erros += validarEmail('contato_email', 'Preencha o EMAIL corretamente!');
		$erros += validarItem('contato_telefone', 'Digite seu Telefone', 'Preencha o TELEFONE corretamente!');
		$erros += validarItem('contato_estado', 'Selecione o Estado', 'Favor selecionar o ESTADO!');
		$erros += validarItem('contato_cidade', 'Digite sua Cidade', 'Preencha a CIDADE corretamente!');
		$erros += validarItem('contato_mensagem', 'Digite a Mensagem', 'Preencha a MENSAGEM corretamente!');
		return $erros ? false : true;
	}

	function adicionaCampo($name_input, $name_campo){
		$campo = array('name_input' => $name_input, 'name_campo' => $name_campo);
		return $campo;
	}

	add_action("init","envia_curriculo");
        	
    function envia_curriculo(){
		if (isset($_POST['form_envieseucurriculo'])){
			if(validarEnvieSeuCurriculo()){
				$campos[1] = adicionaCampo('contato_nome', 'Nome');
				$campos[2] = adicionaCampo('contato_email', 'E-mail');
				$campos[3] = adicionaCampo('contato_telefone', 'Telefone');
				$campos[4] = adicionaCampo('contato_estado', 'Estado');
				$campos[5] = adicionaCampo('contato_cidade', 'Cidade');
				$campos[6] = adicionaCampo('contato_mensagem', 'Mensagem');
				$message = '
					<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
					<html xmlns="http://www.w3.org/1999/xhtml">
					<head>
					<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
					<title>Currículo FAEP</title>
					</head>
					
					<body>
					<table width="600" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#666; border:4px solid #46765b;">
					  <tr>
						<td colspan="2" bgcolor="#46765b" style="padding:10px 10px 14px 10px; text-transform:uppercase; font-weight:bold; color:#FFF">Novo curr&iacute;culo recebido pelo site</td>
					  </tr>';
					  $count=1;
					  foreach($campos as $campo):
					  	$background = (($count%2 == 0) ? 'background:#eeeeee':'');
						  $message.='
						  <tr>
							<td style="padding:10px;'.$background.'"><strong>'.$campo['name_campo'].':</strong></td>
							<td width="80%" style="'.$background.'">'.utf8_decode($_POST[$campo['name_input']]).'</td>
						  </tr>';
						$count++;
					  endforeach;
					$message.='</table>
					</body>
					</html>
				';
				
				require (pcom_envio_validacao_DIR.'class.phpmailer.php');
				require (pcom_envio_validacao_DIR.'class.smtp.php');
				$mail = new PHPMailer();
				$mail->IsHTML(true);
				$mail->Encoding = '8bit';
				$mail->IsSMTP(); // Define que a mensagem será SMTP
				$mail->SMTPAuth = true; // Usa autenticação SMTP? (opcional)
				$mail->Host = get_option('_theme_smtp_host'); // Endereço do servidor SMTP
				$mail->Port = get_option('_theme_smtp_port');
				$mail->Username = get_option('_theme_smtp_user'); // Usuário do servidor SMTP
				$mail->Password = get_option('_theme_smtp_password'); // Senha do servidor SMTP
				//$mail->From = 'dionathanclak@agenciadeinternet.com';
				$mail->From = $_POST['contato_email'];
				//$mail->FromName = utf8_decode('Contato FAEP');
				$mail->FromName = utf8_decode($_POST['contato_nome']);
				//$mail->AddAddress('dionathanclak@agenciadeinternet.com', "Contato FAEP");
				$mail->AddAddress(get_option('_theme_recebe_email_cur'), "Contato FAEP");
				$mail->Subject = utf8_decode("Currículo - FAEP");
				$mail->Body = $message;
				//if (isset($_FILES['contato_arquivo']) && $_FILES['contato_arquivo']['tmp_name']) {
				$mail->AddAttachment($_FILES['contato_arquivo']['tmp_name'], $_FILES['contato_arquivo']['name']);
				//}
				
				if($mail->Send()){
					add_success_message("Currículo enviado com sucesso!") ;
				}
				else{
					add_error_message("Não foi possível enviar seu currículo neste momento. Por favor, tente novamente mais tarde.") ;
				}
			}
		}
	}
	
	function validarEnvieSeuCurriculo(){
		$erros = 0;
		$erros += validarArquivo('contato_arquivo', 'Você deve anexar um CURRÍCULO!');
		$erros += validarItem('contato_nome', 'Digite seu Nome', 'Preencha o NOME corretamente!');
		$erros += validarEmail('contato_email', 'Preencha o EMAIL corretamente!');
		$erros += validarItem('contato_telefone', 'Digite seu Telefone', 'Preencha o TELEFONE corretamente!');
		$erros += validarItem('contato_estado', 'Selecione o Estado', 'Favor selecionar o ESTADO!');
		$erros += validarItem('contato_cidade', 'Digite sua Cidade', 'Preencha a CIDADE corretamente!');
		$erros += validarItem('contato_mensagem', 'Digite a Mensagem', 'Preencha a MENSAGEM corretamente!');
		return $erros ? false : true;
	}