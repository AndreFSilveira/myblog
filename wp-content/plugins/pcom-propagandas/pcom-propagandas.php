<?php

/**
 * Plugin Name: PontoCom Propagandas
 * Plugin URI: http://agenciadeinternet.com/
 * Description: Cadastro de banners publicitarios.
 * Author: PontoCom Ag&ecirc;ncia de Internet
 * Author URI: http://agenciadeinternet.com/
**/

define ('pcom_propagandas_DIR', ABSPATH.PLUGINDIR.'/pcom-propagandas/');
define ('pcom_propagandas_URL', get_bloginfo('siteurl').'/'.PLUGINDIR.'/pcom-propagandas');

//-----------------------------------------------------------------------
//DEFININDO ICONS
//-----------------------------------------------------------------------

add_action( 'admin_head', 'z_icon_propagandas' );
 
function z_icon_propagandas() {
    ?>
    <style type="text/css" media="screen">
		#menu-posts-propagandas .wp-menu-image { background: url(<?php echo pcom_propagandas_URL; ?>/img/icon-propaganda-p.png) no-repeat 6px 6px !important; }
		#menu-posts-propagandas .wp-menu-image, #menu-posts-propagandas:hover .wp-menu-image, #menu-posts-propagandas.wp-has-current-submenu .wp-menu-image { background-position: 4px 5px !important; }
		#icon-edit.icon32-posts-propagandas { background: url(<?php echo pcom_propagandas_URL; ?>/img/icon-propaganda-m.png) no-repeat; }
    </style>
<?php }

	
//--------------------------------------------------------------------
//REGISTRO DO POST_TYPE
//--------------------------------------------------------------------
	
	add_action('init','post_type_propagandas');
	function post_type_propagandas(){
		register_post_type('propagandas', array(
			'labels' => array(
				'name' => 'Propagandas',
				'singular_name' => 'Propaganda',
				'add_new' => 'Adicionar Propaganda',
				'add_new_item' => 'Adicionar Propaganda',
				'edit_item' => 'Editar Propaganda'
			),
			'public' => true,
			'hierarchical' => true,
			'supports' => array('title', 'thumbnail')
		));
	}
	add_action('admin_init', 'admin_propagandas_init');
	
	function admin_propagandas_init(){
		add_meta_box('propagandas_meta_box', 'Dados do propaganda', 'propagandas_meta_box', 'propagandas');
	}	
	
	function propagandas_meta_box(){
		global $post;
		$html = '
			<table class="form-table">
				<tbody>
					
					<tr>
						<td class="first" valign="top">Link Propaganda</td>
						<td>
							<input type="text" name="link_propaganda" size="70" value="' . get_post_meta($post->ID, 'link_propaganda', true) . '" />
						</td>
					</tr>	
				</tbody>
			</table>
		';
		echo $html;
	}
	
	add_action('save_post', 'save_propagandas');
	function save_propagandas(){
		global $post;
		if('propagandas' == $_POST['post_type']){
			if(!current_user_can('edit_page', $post_id)){
				return $post_id;
			}
		}
	
		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){ 
	    	return;
	    }
	    
	    $campos = array('link_propaganda');
	    foreach($_POST as $key => $value){
	    	if(in_array($key, $campos)){
	    		update_post_meta($post->ID, $key, $value);
	    	}
	    }
	}
?>
