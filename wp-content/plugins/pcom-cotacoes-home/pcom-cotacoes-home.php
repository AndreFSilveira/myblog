<?php

/**
 * Plugin Name: PontoCom Cotações Home
 * Plugin URI: http://agenciadeinternet.com/
 * Description: Cadastro de cotações que aparecerão em destaque.
 * Author: PontoCom Ag&ecirc;ncia de Internet
 * Author URI: http://agenciadeinternet.com/
**/

define ('pcom_cotacoes_home_DIR', ABSPATH.PLUGINDIR.'/pcom-cotacoes-home/');
define ('pcom_cotacoes_home_URL', get_bloginfo('siteurl').'/'.PLUGINDIR.'/pcom-cotacoes-home');

//-----------------------------------------------------------------------
//DEFININDO ICONS
//-----------------------------------------------------------------------

add_action( 'admin_head', 'z_icon_cotacoes_home' );
 
function z_icon_cotacoes_home() {
    ?>
    <style type="text/css" media="screen">
		#menu-posts-cotacao_home .wp-menu-image { background: url(<?php echo pcom_cotacoes_home_URL; ?>/img/icon-cotacao-p.png) no-repeat 6px 6px !important; }
		#menu-posts-cotacao_home .wp-menu-image, #menu-posts-cotacao_home:hover .wp-menu-image, #menu-posts-cotacao_home.wp-has-current-submenu .wp-menu-image { background-position: 4px 5px !important; }
		#icon-edit.icon32-posts-cotacao_home { background: url(<?php echo pcom_cotacoes_home_URL; ?>/img/icon-cotacao-m.png) no-repeat; }
    </style>
<?php }

	
//--------------------------------------------------------------------
//REGISTRO DO POST_TYPE
//--------------------------------------------------------------------
	
	add_action('init','post_type_cotacoes_home');
	function post_type_cotacoes_home(){
		register_post_type('cotacao_home', array(
			'labels' => array(
				'name' => 'Cotações - Home',
				'singular_name' => 'Cotação - Home',
				'add_new' => 'Adicionar Cotação',
				'add_new_item' => 'Adicionar Cotação',
				'edit_item' => 'Editar Cotação'
			),
			'public' => true,
			'hierarchical' => true,
			'exclude_from_search' => true,
			'supports' => array('title')
		));
	}
	add_action('admin_init', 'admin_cotacoes_home_init');
	
	function admin_cotacoes_home_init(){
		add_meta_box('cotacoes_home_meta_box', 'Dados da Cotação', 'cotacoes_home_meta_box', 'cotacao_home');
	}	
	
	function cotacoes_home_meta_box(){
		global $post;
		$html = '
			<table class="form-table">
				<tbody>';
					for ($i=1; $i <= 10; $i++) { 
					$html.='
						<tr>
							<td colspan=2><h2 style="margin:0">Cultura '.$i.'</h2></td>
						</tr>
						<tr>
							<td class="first" valign="top">Nome</td>
							<td>
								<input type="text" name="nome_cultura'.$i.'" size="70" value="' . get_post_meta($post->ID, 'nome_cultura'.$i, true) . '" />
							</td>
						</tr>
						<tr>
							<td class="first" valign="top">Valor</td>
							<td>
								<input type="text" name="valor_cultura'.$i.'" size="70" value="' . get_post_meta($post->ID, 'valor_cultura'.$i, true) . '" />
							</td>
						</tr>';
					}
					$html.='
				</tbody>
			</table>
		';
		echo $html;
	}
	
	add_action('save_post', 'save_cotacoes_home');
	function save_cotacoes_home(){
		global $post;
		if('cotacao_home' == $_POST['post_type']){
			if(!current_user_can('edit_page', $post_id)){
				return $post_id;
			}
		}
	
		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){ 
	    	return;
	    }
	    
	    $campos = array();
	    for ($i=1; $i <= 10; $i++) { 
	    	$campos[] = 'nome_cultura'.$i;
	    	$campos[] = 'valor_cultura'.$i;
	    }
	    foreach($_POST as $key => $value){
	    	if(in_array($key, $campos)){
	    		update_post_meta($post->ID, $key, $value);
	    	}
	    }
	}
?>
