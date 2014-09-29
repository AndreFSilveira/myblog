<?php

/**
 * Plugin Name: PontoCom Links
 * Plugin URI: http://agenciadeinternet.com/
 * Description: Cadastro de links.
 * Author: PontoCom Ag&ecirc;ncia de Internet
 * Author URI: http://agenciadeinternet.com/
**/

define ('pcom_links_DIR', ABSPATH.PLUGINDIR.'/pcom-links/');
define ('pcom_links_URL', get_bloginfo('siteurl').'/'.PLUGINDIR.'/pcom-links');

//-----------------------------------------------------------------------
//DEFININDO ICONS
//-----------------------------------------------------------------------

add_action( 'admin_head', 'z_icon_links' );
 
function z_icon_links() {
    ?>
    <style type="text/css" media="screen">
		#menu-posts-link .wp-menu-image { background: url(<?php echo pcom_links_URL; ?>/img/icon-link-p.png) no-repeat 6px 6px !important; }
		#menu-posts-link .wp-menu-image, #menu-posts-link:hover .wp-menu-image, #menu-posts-link.wp-has-current-submenu .wp-menu-image { background-position: 4px 5px !important; }
		#icon-edit.icon32-posts-links { background: url(<?php echo pcom_links_URL; ?>/img/icon-link-m.png) no-repeat; }
    </style>
<?php }

	
//--------------------------------------------------------------------
//REGISTRO DO POST_TYPE
//--------------------------------------------------------------------
	
	add_action('init','post_type_links');
	function post_type_links(){
		register_post_type('link', array(
			'labels' => array(
				'name' => 'Links',
				'singular_name' => 'Link',
				'add_new' => 'Adicionar Link',
				'add_new_item' => 'Adicionar Link',
				'edit_item' => 'Editar Link'
			),
			'public' => true,
			'hierarchical' => true,
			'supports' => array('title')
		));
		flush_rewrite_rules();
	}

	//categorias
	add_action( 'init', 'build_taxonomies_links', 0 );
	function build_taxonomies_links() {
		$labels = array(
		    'name'                => 'Categorias',
		    'singular_name'       => 'Categoria',
		    'search_items'        => 'Buscar Categorias',
		    'all_items'           => 'Todas Categorias',
		    'edit_item'           => 'Editar Categoria', 
		    'update_item'         => 'Atualizar Categoria',
		    'add_new_item'        => 'Adicionar Nova Categoria',
		    'menu_name'           => 'Categorias'
		  ); 
		register_taxonomy('categoria', array('link'), 
			array(
				'hierarchical' => true,
				'show_ui' => true,
				'labels' => $labels, 
				'public' => true
			)
		);
	}

	add_action('admin_init', 'admin_links_init');
	
	function admin_links_init(){
		add_meta_box('links_meta_box', 'Dados do link', 'links_meta_box', 'link');
	}	
	
	function links_meta_box(){
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
	
	add_action('save_post', 'save_links');
	function save_links(){
		global $post;
		if('link' == $_POST['post_type']){
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
?>
