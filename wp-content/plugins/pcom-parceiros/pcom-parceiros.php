<?php

/**
 * Plugin Name: PontoCom Parceiros
 * Plugin URI: http://agenciadeinternet.com/
 * Description: Cadastro de parceiros.
 * Author: PontoCom Ag&ecirc;ncia de Internet
 * Author URI: http://agenciadeinternet.com/
**/

define ('pcom_parceiros_DIR', ABSPATH.PLUGINDIR.'/pcom-parceiros/');
define ('pcom_parceiros_URL', get_bloginfo('siteurl').'/'.PLUGINDIR.'/pcom-parceiros');

//-----------------------------------------------------------------------
//DEFININDO ICONS
//-----------------------------------------------------------------------

add_action( 'admin_head', 'z_icon_parceiros' );
 
function z_icon_parceiros() {
    ?>
    <style type="text/css" media="screen">
		#menu-posts-parceiro .wp-menu-image { background: url(<?php echo pcom_parceiros_URL; ?>/img/icon-parceiro-p.png) no-repeat 6px 6px !important; }
		#menu-posts-parceiro .wp-menu-image, #menu-posts-parceiro:hover .wp-menu-image, #menu-posts-parceiro.wp-has-current-submenu .wp-menu-image { background-position: 4px 5px !important; }
		#icon-edit.icon32-posts-parceiro { background: url(<?php echo pcom_parceiros_URL; ?>/img/icon-parceiro-m.png) no-repeat; }
    </style>
<?php }

	
//--------------------------------------------------------------------
//REGISTRO DO POST_TYPE
//--------------------------------------------------------------------
	
	add_action('init','post_type_parceiros');
	function post_type_parceiros(){
		register_post_type('parceiro', array(
			'labels' => array(
				'name' => 'Parceiros',
				'singular_name' => 'Parceiro',
				'add_new' => 'Adicionar Parceiro',
				'add_new_item' => 'Adicionar Parceiro',
				'edit_item' => 'Editar Parceiro'
			),
			'public' => true,
			'hierarchical' => true,
			'supports' => array('title', 'thumbnail')
		));
	}
	add_action('admin_init', 'admin_parceiros_init');
	
	function admin_parceiros_init(){
		add_meta_box('parceiros_meta_box', 'Dados do Parceiro', 'parceiros_meta_box', 'parceiro');
	}	
	
	function parceiros_meta_box(){
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
	
	add_action('save_post', 'save_parceiros');
	function save_parceiros(){
		global $post;
		if('parceiros' == $_POST['post_type']){
			if(!current_user_can('edit_page', $post_id)){
				return $post_id;
			}
		}
	
		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){ 
	    	return;
	    }
	    
	    $campos = array('valor');
	    foreach($_POST as $key => $value){
	    	if(in_array($key, $campos)){
	    		update_post_meta($post->ID, $key, $value);
	    	}
	    }
	}
?>
