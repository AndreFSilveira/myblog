<?php

/**
 * Plugin Name: PontoCom Clientes
 * Plugin URI: http://agenciadeinternet.com/
 * Description: Cadastro de Clientes.
 * Author: PontoCom Ag&ecirc;ncia de Internet
 * Author URI: http://agenciadeinternet.com/
**/

define ('pcom_clientes_DIR', ABSPATH.PLUGINDIR.'/pcom-clientes/');
define ('pcom_clientes_URL', get_bloginfo('siteurl').'/'.PLUGINDIR.'/pcom-clientes');


//-----------------------------------------------------------------------
//DEFININDO ICONS
//-----------------------------------------------------------------------

add_action( 'admin_head', 'z_icon_clientes' );
 
function z_icon_clientes() {
    ?>
    <style type="text/css" media="screen">
		#menu-posts-cliente .wp-menu-image { background: url(<?php echo pcom_clientes_URL; ?>/img/icon-clientes-p.png) no-repeat 6px 6px !important; }
		#menu-posts-cliente .wp-menu-image, #menu-posts-cliente:hover .wp-menu-image, #menu-posts-cliente.wp-has-current-submenu .wp-menu-image { background-position: 4px 5px !important; }
		#icon-edit.icon32-posts-cliente { background: url(<?php echo pcom_clientes_URL; ?>/img/icon-clientes-m.png) no-repeat; }
    </style>
<?php }

	
//----------------------------------------------------------------------------------
//REGISTRO DO POST_TYPE
//----------------------------------------------------------------------------------

	add_action('init','post_type_cliente');
	function post_type_cliente(){
		register_post_type('cliente', array(
			'labels' => array(
				'name' => 'Clientes',
				'singular_name' => 'Cliente',
				'add_new' => 'Adicionar Cliente',
				'add_new_item' => 'Adicionar Cliente',
				'edit_item' => 'Editar Cliente'
			),
			'public' => true,
			'exclude_from_search' => true,
			'hierarchical' => true,
			'supports' => array('title', 'thumbnail')
		));
	}
	
	add_action('admin_init', 'admin_cliente_init');
	
	function admin_cliente_init(){
		add_meta_box('cliente_meta_box', 'Dados', 'cliente_meta_box', 'cliente');
	}
	
	function cliente_meta_box(){
		
		global $post;
		$html = '
			<table class="form-table">
				<tbody>
					<tr>
						<td class="first" valign="top">Link</td>
						<td>
							<input type="text" name="link" size="70" value="' . get_post_meta($post->ID, 'link', true) . '" />
						</td>
					</tr>
									
				</tbody>
			</table>
		';
		echo $html;
	}
	
	add_action('save_post', 'save_cliente');
	function save_cliente(){
		global $post;
		if('cliente' == $_POST['post_type']){
			if(!current_user_can('edit_page', $post_id)){
				return $post_id;
			}
		}
	
		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){ 
	    	return;
	    }
	    
	    $campos = array('link');
	    foreach($_POST as $key => $value){
	    	if(in_array($key, $campos)){
	    		update_post_meta($post->ID, $key, $value);
	    	}
	    }
	}