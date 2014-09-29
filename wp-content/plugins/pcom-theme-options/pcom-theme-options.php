<?php

/**
 * Plugin Name: PontoCom Op&ccedil;&otilde;es do Tema
 * Plugin URI: http://agenciadeinternet.com/
 * Description: Op&ccedil;&otilde;es personalizadas para o tema.
 * Author: PontoCom Ag&ecirc;ncia de Internet
 * Author URI: http://agenciadeinternet.com/
**/

//--------------------------------------------------------------------------------------
//	OPÇÔES DO TEMA
//--------------------------------------------------------------------------------------
	add_action('admin_menu', 'pcom_menu');
    function pcom_menu() {
        add_theme_page('Op&ccedil;&otilde;es do Tema', 'Op&ccedil;&otilde;es do Tema', 'edit_theme_options', 'pcom-theme-options', 'pcom_theme_options');
    }
    
        
    function pcom_theme_options() {
		$pluginpath = get_bloginfo('url').'/'.PLUGINDIR.'/pcom-theme-options/';
        global $blog_id;
    
        if (!current_user_can('edit_theme_options'))
            wp_die ( __('You do not have sufficient permissions to access this page.'));

        if (isset($_POST['theme'])){
            foreach ($_POST['theme'] as $k => $v){
                update_option ('_theme_'.$k, $v);
            }
		}

        ?>
		<script type="text/javascript">
			var url = "<?php echo $pluginpath ?>"
		</script>
		<style>
			.wrap input[type=text]	{ width: 400px; }
		</style>
		<script type="text/javascript" src="<?php echo $pluginpath ?>jquery-1.10.2.js"></script>
		
		<script type="text/javascript">

			jQuery(function () {
				jQuery('#go').blur(function(){
				    lookupID();
				});
			});
			function lookupID(){
				var erro = 0;				
				jQuery.ajax({
					type: "POST",
					url: url+"ajax-insta.php",
					async: false,
					success: function(html){
						jQuery('#instaid').val(html);
					}
				});
				return false;
			}
		</script>

			<div class="wrap">
				<div class="icon32" id="icon-options-general"><br></div>
				<h2><?php _e('Op&ccedil;&otilde;es do Tema', 'pcom'); ?></h2>
				<form method="POST" action="">
					<h3>Dados Facebook</h3>

					<table class="form-table">
						<tr>
							<th>Usu&aacute;rio</th>
							<td><input type="text" name="theme[facebook_user]" value="<?php echo get_option ('_theme_facebook_user'); ?>" /></td>
						</tr>
					</table>

					<h3>Dados Twitter</h3>

					<table class="form-table">
						<tr>
							<th>Usu&aacute;rio</th>
							<td><input type="text" name="theme[twitter_user]" value="<?php echo get_option ('_theme_twitter_user'); ?>" /></td>
						</tr>
					</table>

					<h3>Dados Youtube</h3>

					<table class="form-table">
						<tr>
							<th>Usu&aacute;rio/Canal</th>
							<td><input type="text" name="theme[youtube_user]" value="<?php echo get_option ('_theme_youtube_user'); ?>" /></td>
						</tr>
					</table>

					<h3>Dados Instagram</h3>
					
					<table class="form-table">
						<tr>
							<th>Usu&aacute;rio</th>
							<td><input type="text" name="theme[instagram_user]" value="<?php echo get_option ('_theme_instagram_user'); ?>" id="go" /></td>
							<input type="hidden" name="theme[instagram_user_id]" value="<?php echo get_option ('_theme_instagram_user_id'); ?>" id="instaid"/>
						</tr>
						
						
					</table>

					<h3>Dados Pinterest</h3>
					
					<table class="form-table">
						<tr>
							<th>Usu&aacute;rio</th>
							<td><input type="text" name="theme[pinterest_user]" value="<?php echo get_option ('_theme_pinterest_user'); ?>" /></td>
						</tr>
					</table>

					<h3>Dados Google+</h3>
					
					<table class="form-table">
						<tr>
							<th>Usu&aacute;rio</th>
							<td><input type="text" name="theme[gplus_user]" value="<?php echo get_option ('_theme_gplus_user'); ?>" /></td>
						</tr>
					</table>


					<h3>Dados SMTP</h3>

					<table class="form-table">
						<tr>
							<th>Host</th>
							<td><input type="text" name="theme[smtp_host]" value="<?php echo get_option ('_theme_smtp_host'); ?>" /></td>
						</tr>
						<tr>
							<th>Porta</th>
							<td><input type="text" name="theme[smtp_port]" value="<?php echo get_option ('_theme_smtp_port'); ?>" /></td>
						</tr>
						<tr>
							<th>Usu&aacute;rio</th>
							<td><input type="text" name="theme[smtp_user]" value="<?php echo get_option ('_theme_smtp_user'); ?>" /></td>
						</tr>
						<tr>
							<th>Senha</th>
							<td><input type="text" name="theme[smtp_password]" value="<?php echo get_option ('_theme_smtp_password'); ?>" /></td>
						</tr>
					</table>

					<h3>E-mails Destinat&aacute;rios Contato</h3>

					<table class="form-table">
						<tr>
							<th>D&uacute;vidas e Sugest&otilde;es</th>
							<td><input type="text" name="theme[email_duvidas_sugestoes]" value="<?php echo get_option ('_theme_email_duvidas_sugestoes'); ?>" /></td>
						</tr>
						<tr>
							<th>Jornalismo e Imprensa</th>
							<td><input type="text" name="theme[email_jornalismo_imprensa]" value="<?php echo get_option ('_theme_email_jornalismo_imprensa'); ?>" /></td>
						</tr>
					</table>

					<h3>E-mails Destinat&aacute;rios Anuncie</h3>

					<table class="form-table">
						<tr>
							<th>Site</th>
							<td><input type="text" name="theme[email_anuncie_site]" value="<?php echo get_option ('_theme_email_anuncie_site'); ?>" /></td>
						</tr>
						<tr>
							<th>Cat&aacute;logo Impresso</th>
							<td><input type="text" name="theme[email_anuncie_catalogo]" value="<?php echo get_option ('_theme_email_anuncie_catalogo'); ?>" /></td>
						</tr>
					</table>

					<p class="submit">
						<input type="submit" value="Salvar altera&ccedil;&otilde;es" class="button-primary" name="Submit">
					</p>
				</form>
			</div>
			
        <?php
    }
