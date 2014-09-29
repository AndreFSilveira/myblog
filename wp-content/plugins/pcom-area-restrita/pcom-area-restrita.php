<?php

/**
 * Plugin Name: PontoCom Area Restrita
 * Plugin URI: http://agenciadeinternet.com/
 * Description: Area Restrita.
 * Author: PontoCom Ag&ecirc;ncia de Internet
 * Author URI: http://agenciadeinternet.com/
**/

define ('pcom_area_restrita_DIR', ABSPATH.PLUGINDIR.'/pcom-area-restrita/');
define ('pcom_area_restrita_URL', get_bloginfo('siteurl').'/'.PLUGINDIR.'/pcom-area-restrita');

//-----------------------------------------------------------------------
//DEFININDO ICONS
//-----------------------------------------------------------------------

add_action( 'admin_head', 'z_icon_area_restrita' );
 
function z_icon_area_restrita() {
    ?>
    <style type="text/css" media="screen">
		#menu-posts-restrito .wp-menu-image { background: url(<?php echo pcom_area_restrita_URL; ?>/img/icon-restrito-p.png) no-repeat 6px 6px !important; }
		#menu-posts-restrito .wp-menu-image, #menu-posts-restrito:hover .wp-menu-image, #menu-posts-restrito.wp-has-current-submenu .wp-menu-image { background-position: 4px 5px !important; }
		#icon-edit.icon32-posts-area-restrita { background: url(<?php echo pcom_area_restrita_URL; ?>/img/icon-restrito-m.png) no-repeat; }
    </style>
<?php }

	
//--------------------------------------------------------------------
//REGISTRO DO POST_TYPE
//--------------------------------------------------------------------

	add_action ('init', 'restrito_custom_post_types');
	function restrito_custom_post_types() {
		$args = array(
			'label' => '&Aacute;rea Restrita',
			'labels' => array(
				'name' => '&Aacute;rea Restrita',
				'singular_name' => 'Restrito',
				'add_new' => 'Adicionar Restrito',
				'add_new_item' => 'Adicionar Restrito'
			),
			'hierarchical' => false,
			'supports' => array ('title','editor','thumbnail','excerpt','revisions','author'),
			'public' => false,
			'show_ui' => true,
			'publicly_queryable' => true,
			'rewrite' => array(
				'slug' => 'restrito',
				'with_front' => false
			),
			'can_export' => true,
			'has_archive' => true,
		);
		register_post_type('restrito', $args);
		flush_rewrite_rules();
	}

//--------------------------------------------------------------------
// SALVA POST_TYPE (NÃO MEXER!!!)
//--------------------------------------------------------------------	
	
	if ($_POST['post_type'] == 'restrito') add_action('save_post', 'restrito_save_postdata');
	function restrito_save_postdata ( $post_id ) {
		
		global $wpdb;

        if (!current_user_can('edit_post', $post_id))
			return $post_id;
			
		if (isset($_POST['restrito'])){
			foreach ($_POST['restrito'] as $k => $v) {
				update_post_meta ($post_id, '_restrito_'.$k, $v);
			}
		}
	}
?>
