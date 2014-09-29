<?php

/**
 * Plugin Name: PontoCom Newsletter
 * Plugin URI: http://agenciadeinternet.com/
 * Description: Cadastro de newsletter.
 * Author: PontoCom Ag&ecirc;ncia de Internet
 * Author URI: http://agenciadeinternet.com/
**/

define ('pcom_newsletter_DIR', ABSPATH.PLUGINDIR.'/pcom-newsletter/');
define ('pcom_newsletter_URL', get_bloginfo('siteurl').'/'.PLUGINDIR.'/pcom-newsletter');

//-----------------------------------------------------------------------
//DEFININDO ICONS
//-----------------------------------------------------------------------

add_action( 'admin_head', 'z_icon_newsletter' );
 
function z_icon_newsletter() {
    ?>
    <style type="text/css" media="screen">
		#menu-posts-cadastronews .wp-menu-image { background: url(<?php echo pcom_newsletter_URL; ?>/img/icon-newsletter-p.png) no-repeat 6px 6px !important; }
		#menu-posts-cadastronews .wp-menu-image, #menu-posts-cadastronews:hover .wp-menu-image, #menu-posts-cadastronews.wp-has-current-submenu .wp-menu-image { background-position: 4px 5px !important; }
		#icon-edit.icon32-posts-cadastronews { background: url(<?php echo pcom_newsletter_URL; ?>/img/icon-newsletter-m.png) no-repeat; }
    </style>
<?php }

	
//--------------------------------------------------------------------
//REGISTRO DO POST_TYPE
//--------------------------------------------------------------------

	/* Cadastro de Newsletter */
	add_action('init','post_type_cadastronews');
	
	function post_type_cadastronews(){
		register_post_type('cadastronews', array(
			'labels' => array(
				'name' => 'Cadastros Newsletter',
				'singular_name' => 'Cadastro Newsletter',
				'add_new' => 'Adicionar Cadastro',
				'add_new_item' => 'Adicionar Cadastro',
				'edit_item' => 'Editar Cadastro'
			),
			'public' => true,
			'hierarchical' => false,
			'exclude_from_search' => true,
			'supports' => array ('custom-fields')
		));
	}
	add_action('admin_init', 'admin_cadastronews_init');
	function admin_cadastronews_init(){
		add_meta_box('cadastronews_meta_box', 'Dados do Cadastro', 'cadastronews_meta_box', 'cadastronews');
	}
	function cadastronews_meta_box(){
		global $post;
		$html = '
			<table class="form-table">
				<tbody>
					<tr>
						<td class="first" valign="top">Email
							<input type="text" name="email" size="35" value="' . get_post_meta($post->ID, 'email', true) . '" />
						</td>
					</tr>
				</tbody>
			</table>
		';
		echo $html;
	}
	add_action('save_post', 'save_cadastronews');
	function save_cadastronews(){
		global $post;
		if('cadastronews' == $_POST['post_type']){
			if(!current_user_can('edit_page', $post_id)){
				return $post_id;
			}
		}
	
		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){ 
			return;
		}

		$campos = array('email');
		foreach($_POST as $key => $value){
			if(in_array($key, $campos)){
				update_post_meta($post->ID, $key, $value);
			}
		}
	}
	add_action('manage_edit-cadastronews_columns', 'cadastronews_columns_header');
	function cadastronews_columns_header(){
		$cols = array(
			'cb' => '<input type="checkbox" />',
			'email' => 'E-mail'
		);
		return $cols;
	}

	add_filter('manage_posts_custom_column' , 'cadastronews_columns');
	function cadastronews_columns($column){
		global $post;
		if($column == 'email') echo '<a href="post.php?action=edit&post='.$post->ID.'">' . get_post_meta($post->ID, 'email', true) . '</a>';
	}
	add_action('init','save_front_cadastronews');
	function save_front_cadastronews(){
		if(count($_POST) && isset($_POST['cadastronews'])){
			$erros = false;
			$data = array();
			foreach($_POST as $key => $value){
				$data[str_replace('news_', '', $key)] = $value;
			}
			
			$obrigatorios = array('email');
			$emailobrigatorio = array('email');
			
			foreach($emailobrigatorio as $key){
				if(!isset($data[$key]) || !strlen($data[$key])){
					add_mensagem_erro_newsletter('Preencha corretamente o email.');
					$erros = true;
					break;
				}
				
				if (!preg_match("/^[a-z0-9_\.\-]+@[a-z0-9_\.\-]*[a-z0-9_\-]+\.[a-z]{2,4}$/", $data[$key])){
					add_mensagem_erro_newsletter('Preencha com um email válido.');
					$erros = true;
					break;
				}
			}
		
			if(existeCadastroNews($data['email'])){
				add_mensagem_erro_newsletter('Já existe um cadastro com este e-mail.');
				$erros = true;
			}
		
			if(!$erros){
				$post = array(
					'post_title' => 'Cadastro na Newsletter',
					'post_status' => 'publish',
					'post_type' => 'cadastronews'
				);
				$post_id = wp_insert_post($post);
			
				foreach($data as $key => $value){
					if(in_array($key, $obrigatorios)){
						update_post_meta($post_id, $key, $value);
					}
				}

				enviaEmailNews();

				add_mensagem_sucesso_newsletter('Cadastro efetuado com sucesso.');
			}
		}
	}

	function existeCadastroNews($email){
		$posts = get_posts(array(
			'post_type' => 'cadastronews',
			'post_status' => 'publish',
			'meta_key' => 'email',
			'meta_value' => $email
		));
		return count($posts);
	}

	function enviaEmailNews(){
		$message = '
			<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
			<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<title>Newsletter Probat Leogap</title>
			</head>
			
			<body>
			<table width="600" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#666; border:4px solid #bbc90d;">
			    <tr>
					<td colspan="2" bgcolor="#bbc90d" style="padding:10px 10px 14px 10px; text-transform:uppercase; font-weight:bold; color:#FFF">Novo cadastro de newsletter pelo site</td>
			    </tr>
			    <tr>
					<td style="padding:10px;"><strong>E-mail:</strong></td>
					<td width="90%">'.utf8_decode($_POST["form_email"]).'</td>
				</tr>
			</table>
			</body>
			</html>
		';
	
	
		require (pcom_newsletter_DIR.'class.phpmailer.php');
		$mail = new PHPMailer();
		$mail->IsHTML(true);
		$mail->Encoding = '8bit';
		$mail->From = 'juliofabiane@agenciadeinternet.com'; 
		$mail->FromName = utf8_decode('Contato Probat Leogap');
		$mail->AddAddress('juliofabiane@agenciadeinternet.com', "Contato Probat Leogap");
		$mail->Subject = utf8_decode("Novo Cadastro de Newsletter - Probat Leogap");
		$mail->Body = $message;
		
		if($mail->Send()){
			//add_message_sucess("Contato enviado com sucesso!") ;
		}
		else{
			add_mensagem_erro_newsletter("Não foi possível enviar a mensagem de cadastro.") ;
		}
	}

	function add_mensagem_erro_newsletter($msg)
	{
	
		if (!isset($_SESSION['mensagens_erro_newsletter'])){
			$_SESSION['mensagens_erro_newsletter'] = array() ;
		}
		
		$_SESSION['mensagens_erro_newsletter'][] = $msg ;
	}
	
	function add_mensagem_sucesso_newsletter($msg)
	{
		if (!isset($_SESSION['mensagem_sucesso_newsletter']))
		{
			$_SSESSION['mensagem_sucesso_newsletter'] = array();
		}
		
		$_SESSION['mensagem_sucesso_newsletter'][] = $msg ;
	}
	
	function get_message_error_newsletter()
	{
		if (isset($_SESSION['mensagens_erro_newsletter']))
		{
			$mensagens =array_merge($_SESSION['mensagens_erro_newsletter']);
			unset($_SESSION['mensagens_erro_newsletter']);
			return $mensagens;
		}
		
	}
	
	function get_message_success_newsletter(){
		if (isset($_SESSION['mensagem_sucesso_newsletter'])){
			$mensagens =array_merge($_SESSION['mensagem_sucesso_newsletter']);
			unset($_SESSION['mensagem_sucesso_newsletter']);
			return $mensagens;
		}
	}

	/* Usar este código para pegar as mensagens*/
	/*
	<?php $mensagens = get_message_error_newsletter(); ?>
	<?php if (is_array($mensagens)): ?>
			<div class="erro">
			<ul>
				<?php foreach ($mensagens as $item): ?>
  				<li><?php echo $item; ?></li>
  				<?php endforeach; ?>
			</ul>
			</div>
	<?php endif; ?>
    <?php $sucess_message = get_message_success_newsletter(); ?>
    <?php if (!empty($sucess_message)): ?>
        <div class="sucesso">
            <ul>
             	<li><?php echo $sucess_message[0]; ?> </li>
            </ul>
        </div>
    <?php endif; ?>
    */