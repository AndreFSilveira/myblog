<?php

/**
 * Plugin Name: PontoCom Frases
 * Plugin URI: http://agenciadeinternet.com/
 * Description: Cadastro de frases.
 * Author: PontoCom Ag&ecirc;ncia de Internet
 * Author URI: http://agenciadeinternet.com/
**/


define ('pcom_frases_DIR', ABSPATH.PLUGINDIR.'/pcom-frases/');
define ('pcom_frases_URL', get_bloginfo('siteurl').'/'.PLUGINDIR.'/pcom-frases');

//-----------------------------------------------------------------------
//DEFININDO ICONS
//-----------------------------------------------------------------------

add_action( 'admin_head', 'z_icon_frases' );
 
function z_icon_frases() {
    ?>
    <style type="text/css" media="screen">
		#menu-posts-frase .wp-menu-image { background: url(<?php echo pcom_frases_URL; ?>/img/icon-frases-p.png) no-repeat 6px 6px !important; }
		#menu-posts-frase .wp-menu-image, #menu-posts-frase:hover .wp-menu-image, #menu-posts-frase.wp-has-current-submenu .wp-menu-image { background-position: 4px 5px !important; }
		#icon-edit.icon32-posts-frases { background: url(<?php echo pcom_frases_URL; ?>/img/icon-frases-m.png) no-repeat; }
    </style>
<?php }

	
//--------------------------------------------------------------------
//REGISTRO DO POST_TYPE
//--------------------------------------------------------------------

	add_action ('init', 'frase_custom_post_types');
	function frase_custom_post_types() {
		$args = array(
			'label' => 'Frases',
			'labels' => array(
				'name' => 'Frases',
				'singular_name' => 'Frase',
				'add_new' => 'Adicionar Frase',
				'add_new_item' => 'Adicionar Frase'
			),
			'hierarchical' => true,
			'supports' => array ('title'),
			'public' => true,
			'can_export' => true,
		);
		register_post_type('frase', $args);
		flush_rewrite_rules();
	}
	
//--------------------------------------------------------------------
// META_BOX
//--------------------------------------------------------------------

	add_action ('admin_menu', 'frase_metabox');
	function frase_metabox () {
		add_meta_box ('frase_meta', 'Informa&ccedil;&otilde;es', 'frase_meta_box', 'frase');
	}
	
	function frase_meta_box () {
		global $post, $wpdb;
		?>
        <style type="text/css">
            .sigla {width:5%;}
            .pequeno {width:17%;}
            .grande {width:100%;}
            .space {margin-left:30px;}
        </style>
        
		<fieldset id="frase_fields">
			<table class="widefat">
				<tr>
					<td>Frase <small>(N&atilde;o use HTML)</small></td>
				</tr>
				<tr>
					<td><textarea class="grande" name="frase[texto]"><?php echo get_post_meta($post->ID, '_frase_texto', 1); ?></textarea></td>
				</tr>
				<tr>
					<td>Autor</td>
				</tr>
				<tr>
					<td><input class="grande" type="text" name="frase[autor]" value="<?php echo get_post_meta($post->ID, '_frase_autor', 1); ?>" /></td>
				</tr>
			</table>
		</fieldset>
		<?php
	}

//--------------------------------------------------------------------
// SALVA POST_TYPE (NÃO MEXER!!!)
//--------------------------------------------------------------------	
	
	if ($_POST['post_type'] == 'frase') add_action('save_post', 'frase_save_postdata');
	function frase_save_postdata ( $post_id ) {
		
		global $wpdb;

        if (!current_user_can('edit_post', $post_id))
			return $post_id;
			
		if (isset($_POST['frase'])){
			foreach ($_POST['frase'] as $k => $v) {
				update_post_meta ($post_id, '_frase_'.$k, $v);
			}
		}
	}
?>